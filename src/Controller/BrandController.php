<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Form\BrandType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BrandController extends AbstractController
{
    /**
     * @Route("/marque", name="brand_index")
     */
    public function index(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $b = new Brand();
        $form = $this->createForm(BrandType::class, $b);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($b);
            $em->flush();

            $this->addFlash('success', 'Marque créée.');
        }

        $brands = $em->getRepository(Brand::class)->findAll();

        return $this->render('brand/index.html.twig', [
            'brands' => $brands,
            'formCreate' => $form->createView(),
        ]);
    }

    /**
     * @Route("/marque/{id}", name="brand_consult")
     */
    public function update(Brand $b = null, Request $request): Response
    {
        if ($b == null)
        {
            $this->addFlash('danger', 'Marque introuvable');
            return $this->redirectToRoute('brand_index');
        }

        $form = $this->createForm(BrandType::class, $b);
        $form->handleRequest($request);

        if ($form->isSubmitted()  && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($b);
            $em->flush();

            $this->addFlash('success', 'Marque mise à jour');
        }

        return $this->render('brand/update.html.twig', [
            'brand' => $b,
            'formUpdate' => $form->createView()
        ]);
    }

    /**
     * @Route("/marque/supprimer/{id}", name="brand_delete")
     */
    public function delete(Brand $b = null)
    {
        if ($b == null)
        {
            $this->addFlash('danger', 'Marque introuvable');
            return $this->redirectToRoute('brand_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($b);
        $em->flush();

        $this->addFlash('success', "Marque supprimée");

        return $this->redirectToRoute('brand_index');
    }
}
