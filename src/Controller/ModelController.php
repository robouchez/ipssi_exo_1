<?php

namespace App\Controller;

use App\Entity\Model;
use App\Form\ModelType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModelController extends AbstractController
{
   /**
     * @Route("/modele", name="model_index")
     */
    public function index(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $m = new Model();
        $form = $this->createForm(ModelType::class, $m);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($m);
            $em->flush();

            $this->addFlash('success', 'Modèle créé.');
        }

        $models = $em->getRepository(Model::class)->findAll();

        return $this->render('model/index.html.twig', [
            'models' => $models,
            'formCreate' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modele/{id}", name="model_consult")
     */
    public function update(Model $m = null, Request $request): Response
    {
        if ($m == null)
        {
            $this->addFlash('danger', 'Modèle introuvable');
            return $this->redirectToRoute('model_index');
        }

        $form = $this->createForm(ModelType::class, $m);
        $form->handleRequest($request);

        if ($form->isSubmitted()  && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($m);
            $em->flush();

            $this->addFlash('success', 'Modèle mis à jour');
        }

        return $this->render('model/update.html.twig', [
            'model' => $m,
            'formUpdate' => $form->createView()
        ]);
    }

    /**
     * @Route("/modele/supprimer/{id}", name="model_delete")
     */
    public function delete(Model $m = null)
    {
        if ($m == null)
        {
            $this->addFlash('danger', 'Modèle introuvable');
            return $this->redirectToRoute('model_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($m);
        $em->flush();

        $this->addFlash('success', "Modèle supprimé");

        return $this->redirectToRoute('model_index');
    }
}
