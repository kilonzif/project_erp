<?php
/**
 * Created by PhpStorm.
 * User: Sowee - Makedu
 * Date: 9/6/2018
 * Time: 3:35 PM
 */

namespace App\Classes;


class SystemMail
{

    private $to;
    private $from;
    private $view;
    private $subject;
    private $attachment;

    public function __construct() {

    }

    public function send() {

        $to = $this->to;
        $headers[] = 'From: Association of African Universities <no-reply@aau.org>';
        $subject = $this->subject;
        $message = $this->view;
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';
        mail($to, $subject, $message, implode("\r\n", $headers));
    }

    /**
     * @param $email
     *
     * @return $this
     */
    public function to( $email ) {
        $this->to = $email;

        return $this;
    }

    /**
     * @param $from
     *
     * @return $this
     */
    public function from( $from ) {
        $this->from = $from;

        return $this;
    }

    /**
     * @param $view
     * @param $data
     *
     * @return $this
     * @throws \Throwable
     */
    public function markdown( $view, $data = null ) {
        $this->view = view( $view, $data )->render();
        return $this;

    }

    /**
     * @param $subject
     *
     * @return $this
     */
    public function subject( $subject ) {
        $this->subject = $subject;
        return $this;
    }

    public function attachment(){

    }
}