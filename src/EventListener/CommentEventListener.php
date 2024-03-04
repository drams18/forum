<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Event;

use App\Entity\Comment;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;

class CommentEventListener implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            AfterEntityPersistedEvent::class => 'sendEmailOnCommentCreation',
        ];
    }

    public function sendEmailOnCommentCreation(AfterEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Comment) {

            $subject = 'Nouveau commentaire';
            $recipientEmail = 'arphandrame0@gmail.com';

            $email = (new Email())
                ->from('noreply@example.com')
                ->to($recipientEmail)
                ->subject($subject)
                ->html('<p>Un nouveau commentaire a été ajouté à votre formulaire.</p>');

            $this->mailer->send($email);
        }
    }
}
