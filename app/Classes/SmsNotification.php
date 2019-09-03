<?php
/**
 * Created by PhpStorm.
 * User: Sowee - Makedu
 * Date: 9/6/2018
 * Time: 3:40 PM
 */

namespace App\Classes;


class SmsNotification
{
    /**
     * The sender id/name.
     *
     * @var string
     */
    public $from;


    /**
     * The phone number(s) of recipient(s).
     *
     * @var string|array
     */
    public $to;


    /**
     * The content of the message.
     *
     * @var string
     */
    public $content;

    public function dispatch(){
        $query = [
            "key" => config('services.sms.api_key'),
            "to" => is_array($this->to)?implode(',', $this->to):$this->to,
            "msg" => $this->content,
            "sender_id" => !empty($this->from)?$this->from: config('services.sms.from')
        ];

        $url = config('services.sms.url')
            . "?key=".$query['key']
            . "&to=".$query['to']
            . "&msg=".urlencode($query['msg'])
            . "&sender_id=".$query['sender_id'];

        try {
            $response = file_get_contents( $url );
//            $this->logSms( $response );

        } catch ( \Exception $e ) {
//            Log::error($e->getTraceAsString());
        }

    }
}