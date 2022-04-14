<?php

namespace App\Controller;


use App\Entity\Avis;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AvisController extends AbstractController
{
    /**
     * @Route("/avis", name="avis")
     */
    public function index(): Response
    {

        //on récupère l'ensemble de la table avis
        $repo = $this->getDoctrine()->getRepository(Avis::class);

        $avis = $repo->findAll();
        return $this->render('avis/index.html.twig', [
            'avis' => $avis,
            'current_menu' => 'avis'
        ]);
    }

    /**
     * @Route("/avis/new", name="avis_create")
     * @Route("/avis/{id}/edit", name="avis_edit")
     */
    public function form(Avis $avis = null, Request $request, EntityManagerInterface $manager)
    {
        //voir si l avis existe déja
        if (!$avis) {
            $avis = new Avis();
        }
        //créer un formualaire avec les champs requis pour la création d'un avis
        $form = $this->createFormBuilder($avis)
            ->add('nom')
            ->add('prenom')
            ->add('contenu')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setCreatedAt(new \DateTimeImmutable());
            //on insert en base
            $manager->persist($avis);
            $manager->flush();

            return $this->redirectToRoute('avis');
        }

        return $this->render('avis/create.html.twig', [
            'formAvis' => $form->createView(),
            'editMode' => $avis->getId() !== null,
            'current_menu' => 'avis'
        ]);
    }

    /**
     * @Route("/avis/{id}/delete", name="avis_delete")
     */
    public function delete(Avis $avis)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($avis);
        $manager->flush();

        return $this->redirectToRoute("avis");
    }
}
