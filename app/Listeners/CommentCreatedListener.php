<?php

namespace App\Listeners;

use App\Notifications\CommentCreatedNotification;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
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
    public function handle(CommentCreated $commentCreated)
    {
//        dd($commentCreated->comment->commenter_id);
        User::find($commentCreated->comment->commenter_id)->notify(new CommentCreatedNotification($commentCreated->comment));
    //   App\User::find(1)->notify(new App\Notifications\CommentCreatedNotification(Laravelista\Comments\Comment::find(8)));
    }
}
