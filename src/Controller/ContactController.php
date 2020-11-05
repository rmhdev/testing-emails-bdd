<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/", name="contact")
     */
    public function index(): Response
    {
        $form = $this->createForm(ContactFormType::class);

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
}
