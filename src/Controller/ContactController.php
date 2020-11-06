<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Form\Model\ContactDTO;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/", name="contact")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mailer->send(
                $this->createEmail($form->getData())
            );

            return $this->redirectToRoute('contact_success');
        }

        return $this->render('contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/email-sent", name="contact_success")
     */
    public function success(): Response
    {
        return $this->render('success.html.twig');
    }

    private function createEmail(ContactDTO $contact): TemplatedEmail
    {
        return (new TemplatedEmail())
            ->from('no-reply@mywebsite.example.com')
            ->to('contact@mywebsite.example.com')
            ->subject('Contact received')
            ->htmlTemplate('email/contact.html.twig')
            ->textTemplate('email/contact.txt.twig')
            ->context([
                'contact' => $contact,
            ])
        ;
    }
}
