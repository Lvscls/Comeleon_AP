<?php

namespace App\Controller;

use App\Entity\Prestations;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PrestationController extends AbstractController
{
    /**
     * @Route("/prestation", name="prestation")
     */
    public function index(): Response
    {
        //on récupère l'ensemble de la table prestation
        $repo = $this->getDoctrine()->getRepository(Prestations::class); 

        $prestations = $repo->findAll();

        return $this->render('prestation/index.html.twig', [
            'prestations' => $prestations,
            'current_menu' => 'prestation'
        ]);
    }


    /**
     * @Route("/prestation/new", name="prestation_create")
     * @Route("/prestation/{id}/edit", name="prestation_edit")
     */
    public function form(Prestations $prestation = null,Request $request, EntityManagerInterface $manager){
                //voir si la prestation existe déja
        if(!$prestation){
            $prestation = new Prestations();
        }
        //créer un formualaire avec les champs requis pour la création d'une prestation
        $form = $this->createFormBuilder($prestation)
                     ->add('libelle')
                     ->add('prix')
                     ->add('description')
                     ->add('image')
                     ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //on insert en base
            $manager->persist($prestation);
            $manager->flush();

            return $this->redirectToRoute('prestation');
        }

        return $this->render('prestation/create.html.twig',[
            'formPrestation' => $form->createView(),
            'editMode' =>$prestation->getId() !== null,
            'current_menu' => 'prestation'
        ]);
    }

    /**
     * @Route("/prestation/{id}/delete", name="prestation_delete")
     */
    public function delete(Prestations $prestation)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($prestation);
        $manager->flush();

        return $this->redirectToRoute("prestation");
    }
}
