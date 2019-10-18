<?php

namespace App\Services;

use Doctrine\Common\Persistence\ObjectManager;


class StatService {

    private $manager;



    public function __construct(ObjectManager $manager){
        $this->manager = $manager;
    }

    public function getStats(){
        $users    = $this->getUsersCount();
        $ads      = $this->getAdsCount();
        $comments = $this->getCommentsCount();
        $bookings = $this->getBookingsCount();

        return compact('users','ads','comments','bookings');
    }

    public function getAdsStats($direction){
        
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
            FROM App\Entity\Comment c
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a
            ORDER BY note '.$direction
        )
        ->setMaxResults(5)
        ->getResult();
    }
        
    public function getUsersCount(){
       return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }
    public function getAdsCount(){
       return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Ad a')->getSingleScalarResult();
    }
    public function getCommentsCount(){
       return $this->manager->createQuery('SELECT COUNT(b) FROM App\Entity\Comment b')->getSingleScalarResult();
    }
    public function getBookingsCount(){
       return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Booking c')->getSingleScalarResult();
    }
}