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

    public function sendEmail(string $recipient, string $body): void
    {
        $email = (new Email())
            ->from('forum@form.com')
            ->to($recipient)
            ->subject('Nouveau commentaire ajouté - forum@form.com')
            ->text($body);
    
        $this->mailer->send($email);
    }
    
}
