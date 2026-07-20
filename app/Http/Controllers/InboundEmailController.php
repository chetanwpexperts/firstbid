<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessIncomingJob;
use App\Models\InboundEmail;
use App\Models\UpworkJob;
use App\Models\User;
use App\Services\UpworkEmailParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InboundEmailController extends Controller
{
    /**
     * Cloudflare Email Worker calls this: /api/inbound-email/{secret}
     * Body: {to, from, subject, html}. Must return 2xx fast.
     */
    public function handle(Request $request, string $secret, UpworkEmailParser $parser)
    {
        if ($secret !== config('services.inbound_email.secret')) {
            return response()->json(['error' => 'unauthorized'], 401);
        }

        $to = (string) $request->input('to', '');
        $from = (string) $request->input('from', '');
        $subject = (string) $request->input('subject', '');
        $html = (string) $request->input('html', '');

        $user = null;
        if (preg_match('/^u_([a-z0-9]+)@/i', $to, $m)) {
            $user = User::where('webhook_token', $m[1])->first();
        }

        $inbound = InboundEmail::create([
            'user_id'     => $user?->id,
            'to_address'  => $to,
            'from_address' => $from,
            'subject'     => $subject,
            'html'        => $html,
            'status'      => 'received',
        ]);

        if (! $user) {
            $inbound->update(['status' => 'unknown_user']);

            return response()->json(['ok' => true, 'status' => 'unknown_user']);
        }

        if (str_contains($from, 'forwarding-noreply@google.com') || str_contains($subject, 'Forwarding Confirmation')) {
            $inbound->update(['status' => 'verification']);
            Log::info("Gmail forwarding verification email for user {$user->id}");

            return response()->json(['ok' => true, 'status' => 'verification']);
        }

        $jobs = $parser->parse($html, $subject);

        if (empty($jobs)) {
            $inbound->update(['status' => 'no_jobs']);

            return response()->json(['ok' => true, 'status' => 'no_jobs', 'count' => 0]);
        }

        $count = 0;

        foreach ($jobs as $j) {
            if ($j['ciphertext'] && UpworkJob::where('user_id', $user->id)->where('ciphertext', $j['ciphertext'])->exists()) {
                continue;
            }

            $job = UpworkJob::create([
                'user_id'          => $user->id,
                'source'           => 'email',
                'ciphertext'       => $j['ciphertext'],
                'title'            => $j['title'] ?? 'Untitled',
                'description'      => $j['description'] ?? '',
                'job_url'          => $j['url'] ?? null,
                'job_type'         => $j['job_type'] ?? null,
                'budget_display'   => $j['budget'] ?? 'See job post',
                'payment_verified' => true, // unknown in emails — don't false-flag
                'uphunt_score'     => null,
                'raw_payload'      => ['email_id' => $inbound->id, 'parsed' => $j],
            ]);

            ProcessIncomingJob::dispatch($job->id);
            $count++;
        }

        $inbound->update(['status' => 'parsed', 'jobs_found' => $count]);

        return response()->json(['ok' => true, 'status' => 'parsed', 'count' => $count]);
    }
}
