<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Booking;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Appfixtures extends Fixture
{   
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){

        $this->encoder = $encoder;

    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Ange Martial')
                  ->setLastName('Koffi')
                  ->setEmail('koffi@sf4.com')
                  ->setHash($this->encoder->encodePassword($adminUser,'password'))
                  ->setPicture('https://avatars.io/instagram/angemartialkoffi')
                  ->setIntroduction($faker->sentence())
                  ->setDescription('<p>' .join('</p><p>', $faker->paragraphs(2)) .'</P>')
                  ->addUserRole($adminRole);
            $manager->persist($adminUser);

        //gestion des users

        $users = [];
        $genres = ['male','female'];

        for($i = 1; $i <= 10; $i++){
            $user = new User();
           
            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1 , 99) . '.jpg';

           // if($genre == 'male') $picture = $picture . 'men/' . $pictureId;
           // else $picture = $picture . 'women/' . $pictureId;  tout cece est mi en condition tertaire en dessous.

            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstname($faker->firstName($genres))
                 ->setLastname($faker->lastName())
                 ->setEmail($faker->email())
                 ->setIntroduction($faker->sentence())
                 ->setDescription('<p>' .join('</p><p>', $faker->paragraphs(2)) .'</P>')
                 ->setHash($hash)
                 ->setPicture($picture); 

            $manager->persist($user);
            $users [] = $user;
        }

        //gestion des annonces 

        for( $i = 1; $i <= 30; $i++){
        
        $ad = new Ad();

        $title           = $faker->sentence();
        $coverImage      = $faker->imageUrl(1000,350);
        $introduction    = $faker->paragraph(2);
        $content         = '<p>' .join('</p><p>', $faker->paragraphs(5)) .'</P>';
        $user            = $users[mt_rand(0 , count($users) -1)];

        $ad->setTitle($title)
           
           ->setCoverImage($coverImage)
           ->setIntroduction($introduction)
           ->setContent($content)
           ->setPrice(mt_rand (20 , 80))
           ->setRooms(mt_rand(1 , 5))
           ->setAuthor($user);

           for($j = 1; $j <= mt_rand(2 , 5); $j++){
                $image = new Image();

                $image->setUrl($faker->imageUrl()) 
                      ->setCaption($faker->sentence()) 
                      ->setAd($ad);

            $manager->persist($image);
           }
           // gestion des reservations 

           for($j = 1; $j <= mt_rand(0, 10); $j++){
                $booking = new Booking();

                $createdAt = $faker->dateTimeBetween('-6 months');
                $startDate = $faker->dateTimeBetween('-3 months');

                $duration = mt_rand(3, 10);
                $endDate = (clone $startDate)->modify("+$duration day");
                $amount = $ad->getPrice() * $duration;
                $booker = $users[mt_rand(0, count($users) -1)];
                $comment = $faker->paragraph();

                $booking->setCreatedAt($createdAt)
                        ->setStartDate($startDate)
                        ->setEndDate($endDate)
                        ->setAmount($amount)
                        ->setBooker($booker)
                        ->setAd($ad)
                        ->setComment($comment);

                $manager->persist($booking);

                // gestion des commentaires
                if(mt_rand(0, 1)){
                    $comment = new Comment();

                    $comment->setContent($faker->paragraph())
                            ->setAuthor($booker)
                            ->setRating(mt_rand(1, 5))
                           ->setAd($ad);

                   $manager->persist($comment);
                }
           }
        
        $manager->persist($ad);
    }

        $manager->flush();
    }
}
