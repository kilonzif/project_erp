<?php

namespace App\Notifications;

use App\Comment;
use App\Report;
use Illuminate\Bus\Queueable;
//use Illuminate\Mail\Message;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CommentCreatedNotification extends Notification {
	use Queueable;

	private $comment;
	//private $report;
	/**
	 * Create a new notification instance.
	 *
	 * @param $comment
	 */
	public function __construct($comment) {
		$this->comment = $comment;
		//	$this->report = $report;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function via($notifiable) {
		return ['mail'];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable) {
		try {

			$report = Report::find($this->comment->commentable_id);
			// $user = Report::find($this->report->user->name);
			$emails = ($report->ace->emails->pluck('email')->toArray());
			//dd($this->comment->comment);
			// $this->report->user_id
			// $data = array('name' => 'user', "body" => $this->comment->comment, 'commentername' => $this->comment->commenter->name, 'createdat' => $this->comment->created_at->diffForHumans());

			// Mail::send('emails.mail', $data, function ($message) use ($emails) {
			// 	// dd($message);
			// 	$message->to('edem.gbeku@makeduconsult.com', 'makeduconsult')
			// 		->cc($emails)
			// 		->subject('A Comment  Has Been Added');
			// 	$message->from('info@aau.org', 'AAU-MEL');
			// });

//////////////////

		} catch (\Exception $e) {

			$emails = [];
			Log::error($e->getMessage());
		}

		return (new MailMessage)

			->line('A Comment  Has Been Added To Your Report By ' . $this->comment->commenter->name)
			//->line(" at " . $this->comment->created_at)
			->line('Comment: ' . '"' . $this->comment->comment . '"')
			//->line('Kindly Click Here To View ')
			->action('Click Here To View', route('report_submission.view', [\Illuminate\Support\Facades\Crypt::encrypt($report->id)]))
			->cc($emails)
			->subject('Report Review')
			->line('Thank you for using our application!');

	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function toArray($notifiable) {
		return [
			//
		];
	}
}
