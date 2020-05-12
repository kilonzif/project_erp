<?php

namespace App\Listeners;

use App\Notifications\CommentCreatedNotification;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Laravelista\Comments\Events\CommentCreated;

class CommentCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param CommentCreated $comment
     * @return void
     */
    public function handle(\App\Events\CommentCreated $commentCreated) {
        try {
            User::find($commentCreated->comment->commenter_id)->notify(new CommentCreatedNotification($commentCreated->comment));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
