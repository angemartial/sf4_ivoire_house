<?php

namespace App\Controller;

use App\Services\StatService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(ObjectManager $manager,StatService $statsService)
    {
        //$users = $statsService->getUsersCount();
        //$ads = $statsService->getAdsCount();
       // $comments = $statsService->getCommentsCount();
       // $bookings = $statsService->getBookingsCount();

       $stats = $statsService->getStats();
        
        $bestAds = $statsService->getAdsStats('DESC');
        
        $worstAds = $statsService->getAdsStats('ASC');
       
        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
            'bestAds' => $bestAds,
            'worstAds' => $worstAds
        ]);
    }
}
