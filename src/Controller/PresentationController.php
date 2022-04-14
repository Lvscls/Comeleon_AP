<?php

namespace App\Controller;

use App\Entity\Presentation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PresentationController extends AbstractController
{
    /**
     * @Route("/presentation", name="presentation")
     */
    public function index(): Response
    {
        // récupère l'ensemble de la table présentation dans le repository qu'on stocke dans un objet prest
        $prest = $this->getDoctrine()->getRepository(Presentation::class)->findAll();


        return $this->render('presentation/index.html.twig', [
            'presentations'=> $prest,
            'current_menu' => 'presentation'
        ]);
    }

    /**
     * @Route("/presentation/new", name="presentation_new")
     * @Route("/presentation/{id}/edit", name="presentation_edit")
     */
    public function formPresentation(Presentation $presentation = null, Request $request, EntityManagerInterface $manager)
    {
        //voir si la presentation existe déja
        if(!$presentation){
            $presentation = new Presentation();
        }
        //créer un formualaire avec les champs requis pour la création d'une présentation
        $form = $this->createFormBuilder($presentation)
                     ->add('titre')
                     ->add('contenu')
                     ->add('image')
                     ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            //insert en base
            $manager->persist($presentation);
            $manager->flush();

            return $this->redirectToRoute('presentation');
        }

        return $this->render('presentation/page.html.twig',[
            'formPresentation' => $form->createView(),
            'editMode' =>$presentation->getId() !== null,
            'current_menu' => 'presentation'
        ]);
    }
}
