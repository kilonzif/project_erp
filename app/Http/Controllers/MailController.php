<?php

namespace App\Http\Controllers;
use App\Comment;
use App\Http\Controllers\Controller;
use App\Notifications\CommentCreatedNotification;
use App\User;

class MailController extends Controller {

	public function basic_email() {

		$comment = new Comment();
		$comment->commenter_id = 2;
		$comment->commentable_id = 19;
		$comment->commentable_type = 'App\Report';
		$comment->comment = 'This is a comment';
		$comment->save();
		User::find(2)->notify(new CommentCreatedNotification(Comment::find(6)));

		$data = array('name' => "edem", "body" => "Test mail");

		// Mail::send('emails.mail', $data, function ($message) {
		// 	$message->to('edem.gbeku@makeduconsult.com', 'makeduconsult')
		// 		->cc('edemgbk@gmail.com')
		// 		->subject('Artisans Web Testing Mail');
		// 	$message->from('edemgbk@gmail.com', 'edem gbeku');
		// });

		// echo "email sent,check inbox";

	}

}
