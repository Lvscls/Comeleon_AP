<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, EntityManagerInterface $manager): Response
    {
        $contact = new Contact();

        $form = $this->createFormBuilder($contact)
                    ->add('auteur')
                    ->add('email')
                    ->add('contenu')
                    ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

           $manager->persist($contact);
           $manager->flush();

           return $this->redirectToRoute("contact");
        }

        return $this->render('contact/index.html.twig',[
            'formcontact' => $form->createView(),
            'current_menu' => 'contact'
        ]);
    }
}
