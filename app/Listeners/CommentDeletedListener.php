<?php

namespace App\Listeners;
use App\Notifications\CommentDeletedNotification;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravelista\Comments\Events\CommentDeleted;



class CommentDeletedListener
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
    public function handle(CommentDeleted $commentDeleted)
    {
        //
        User::find($commentDeleted->comment->commenter_id)->notify(new CommentDeletedNotification($commentDeleted->comment));

//   App\User::find(1)->notify(new App\Notifications\CommentDeletedNotification(Laravelista\Comments\Comment::find(8)));
    }
}
