<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class MailerController extends AbstractController
{
    #[Route('/mailer', name: 'app_mailer')]
    public function index(MailerInterface $mailer): Response
    {
        $email = (new Email())
        ->from('test@example.com')
        ->to('recipient@example.com')
        ->subject('Test Email')
        ->text('This is a test email.')
        ->html('<p>This is a test email.</p>');

    $mailer->send($email);

    return new Response('Email sent successfully!');
      
    }
}
