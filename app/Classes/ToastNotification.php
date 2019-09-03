<?php
/**
 * Created by PhpStorm.
 * User: Sowee - Makedu
 * Date: 9/5/2018
 * Time: 9:40 PM
 */

namespace App\Classes;


class ToastNotification
{
    public $title;
    public $message;
    public $type;
    public $position;
    public $isFullWith;

    /**
     * Notification constructor.
     *
     * @param string $title
     * @param string $message
     * @param string $type
     * @param string $position
     * @param bool $isFullWidth
     */
    public function __construct($title, $message, $type = 'info', $position = 'right', $isFullWidth = false) {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->position = $position;
        $this->isFullWith = $isFullWidth;

    }

}