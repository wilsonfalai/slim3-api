<?php

namespace App\Services;

use Swift_Mailer;
use Swift_Message;

class Email
{
    protected $mailer;

    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send($to = [], $subject = "", $html = "", $text = "")
    {
        $message = Swift_Message::newInstance();
        $message->setTo($to);
        $message->setFrom(['support@' . $_SERVER['CLIENT_URL'] => $_SERVER['CLIENT_NAME']]);
        $message->setSubject($subject);
        $message->setBody($html, 'text/html');
        $message->addPart($text, 'text/plain');

        if (filter_var($_SERVER['APP_SENDMAIL'], FILTER_VALIDATE_BOOLEAN)) {
            $out = $this->mailer->send($message);
        } else {
            $out = true;
        }

        if ($out) {
            return true;
        }
        return false;
    }
}
