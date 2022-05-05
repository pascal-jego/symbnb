<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo): Response
    {

        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', compact('ads'));
    }

    /**
     * Permet de créer une annonce
     * 
     * @Route("/ads/new", name="ads_create")
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $ad = new Ad();
   
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest(($request));


        if ($form->isSubmitted() && $form->isValid()) {

            // traitements des images secondaires
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $entityManager->persist($image);
            }

            $entityManager->persist($ad);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * Permet d'éditer une annonce
     * 
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * 
     * @return Response()
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest(($request));

        if ($form->isSubmitted() && $form->isValid()) {

            // traitements des images secondaires
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $entityManager->persist($image);
            }

            $entityManager->persist($ad);
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Les modifications de l'annonce <strong>{$ad->getTitle()}</strong> ont bien été enregistrées !"
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/edit.html.twig', [
            "form" => $form->createView(), 
            "ad" => $ad
        ]);
    }

    /**
     * Permet d'afficher une seule annonce
     *
     * @Route("/ads/{slug}", name="ads_show" )
     * 
     * @return Response
     */
    public function show(Ad $ad)
    {
        // Je récupeère l'annonce qui corrspond au slug !
        // $ad = $repo->findOneBySlug($slug);

        return $this->render('ad/show.html.twig', compact('ad'));
    }
}
