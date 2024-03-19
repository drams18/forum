<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(string $subject, string $recipient, string $body): void
    {
        $email = (new Email())
            ->from('arphandrame0@gmail.com')
            ->to($recipient)
            ->subject($subject)
            ->text($body);

        $this->mailer->send($email);
    }
}
