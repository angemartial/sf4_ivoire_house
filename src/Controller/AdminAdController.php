<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use App\services\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{


   /**
    * Undocumented function

    * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index") 
    * 
    */
    public function index(AdRepository $repo, $page,PaginationService $pagination)
    {   

        $pagination->setEntityClass(Ad::class)
                   ->setPage($page);

             
      // $limit = 10;
      //  $start = $page * $limit - $limit;

      // $total = count($repo->findAll());
      // $pages = ceil($total / 10);

        return $this->render('admin/ad/index.html.twig', [
            'pagination' => $pagination
        ]);
    }


    /**
     * permet d'éditer une annonce
     * 
     *@Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     * @param Ad $ad
     * @return Response
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AnnonceType::class, $ad);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre annonce <strong>{$ad->getTitle()}</strong> a bien été modifiée !"
            );
        }


        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);

    }


    /**
     * permet de supprimer une annonce
     * 
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     *
     * @param Ad $ad
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Ad $ad,Request $request,ObjectManager $manager)
    {
        if(count($ad->getBookings()) > 0){
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'annonce <strong> {$ad->getTitle()} 
                </strong> car elle contient déjà des réservations "
            ); 
        }
        else{
            $manager->remove($ad);
            $manager->flush();

              $this->addFlash(
                'success',
                "L'annonce {$ad->getTitle()} a bien été supprimée "
        );
        }
        

        return $this->redirectToRoute('admin_ads_index');

    }
}
