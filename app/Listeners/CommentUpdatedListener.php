<?php

namespace App\Listeners;
use App\User;
use App\Notifications\CommentUpdatedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravelista\Comments\Events\CommentUpdated;

class CommentUpdatedListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(CommentUpdated $commentUpdated)
    {
        //

        User::find($commentUpdated->comment->commenter_id)->notify(new CommentUpdatedNotification($commentUpdated->comment));

//      App\User::find(1)->notify(new App\Notifications\CommentUpdatedNotification(Laravelista\Comments\Comment::find(8)));


    }
}
