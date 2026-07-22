<?php

namespace App\View\Composers;

use Illuminate\View\View;

/**
 * Shares the notification-bell data (unseen job count + latest unseen
 * jobs) with every page — every blade in this app extends 'layout',
 * and the bell lives in layout's nav, so composing on 'layout' itself
 * is the one place that covers all of them without touching every
 * controller action individually.
 */
class NotificationsComposer
{
    public function compose(View $view): void
    {
        $user = auth()->user();

        if (! $user) {
            $view->with(['unseenJobsCount' => 0, 'unseenJobs' => collect()]);

            return;
        }

        // A user who's never had last_seen_jobs_at set (existing users
        // right after this shipped, or a brand new signup) sees unseen
        // jobs from the last 7 days rather than their entire history.
        $since = $user->last_seen_jobs_at ?? now()->subDays(7);

        // Fresh builder per call, same reasoning as DashboardController —
        // upworkJobs() returns a new query every time it's invoked.
        $unseen = fn () => $user->upworkJobs()->where('created_at', '>', $since);

        $view->with([
            'unseenJobsCount' => $unseen()->count(),
            'unseenJobs'      => $unseen()->latest()->limit(5)->get(),
        ]);
    }
}
