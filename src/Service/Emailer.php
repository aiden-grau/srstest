<?php
// src/Service/Emailer.php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Emailer
{

    public function __construct(MailerInterface $mailer) {
      $this->mailer = $mailer;
    }

    public function sendUserEmail($data)
    {
        $email = (new Email())
            ->from('nobody@all.com')
            ->to($data['email'])
            ->subject('Hey ' . $data['name'])
            ->text('Thank you for your message ' . $data['name'] . '! I will get back to you as soon as my mail stops getting trapped...')
            ->html('<h4>Thank you for your message ' . $data['name'] . '!</h4><p>I will get back to you as soon as my mail stops getting trapped...</p>');

        $this->mailer->send($email);
    }

    public function sendAdminEmail($data)
    {
        $email = (new Email())
            ->from('nobody@all.com')
            ->to('argon.is.alive@gmail.com')
            ->subject('New message')
            ->text('Check html... ;)')
            ->html('<p>Received new message from ' . $data['name'] . ' (' . $data['email'] . ')</p><p>' . $data['message'] . '</p>');

        $this->mailer->send($email);
    }

}
