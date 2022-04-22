<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
    public function create()
    {
        $ad = new Ad();
        
        $form = $this->createForm(AdType::class, $ad);
            
        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
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

        return $this->render('ad/show.html.twig', compact('ad')     );
    }
    
}