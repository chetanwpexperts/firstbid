<?php

namespace App\Console\Commands;

use App\Models\InboundEmail;
use App\Models\UpworkJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PruneOldJobs extends Command
{
    protected $signature = 'jobs:prune';

    protected $description = 'Delete upwork_jobs and inbound_emails older than 7 days';

    public function handle(): int
    {
        $cutoff = now()->subDays(7);

        $jobsDeleted = UpworkJob::where('created_at', '<', $cutoff)->delete();
        $emailsDeleted = InboundEmail::where('created_at', '<', $cutoff)->delete();

        $message = "jobs:prune deleted {$jobsDeleted} upwork_jobs and {$emailsDeleted} inbound_emails older than 7 days";
        Log::info($message);
        $this->info($message);

        return self::SUCCESS;
    }
}
