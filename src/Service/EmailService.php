<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($recipientEmail, $subject, $body)
    {
        $email = (new Email())
            ->from('migueltortg@gmail.com')
            ->to($recipientEmail)
            ->subject($subject)
            ->html($body);

        $this->mailer->send($email);
    }

    public function sendHtmlEmail(
        string $to,
        string $subject,
        string $htmlBody,
        string $from = 'default@example.com'
    ): void {
        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->html($htmlBody);

        $this->mailer->send($email);
    }
}
