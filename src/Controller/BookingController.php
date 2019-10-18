<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    /**
     * permet de créer une réservation 
     * 
     * @Route("/ads/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     */
    public function book(Ad $ad,ObjectManager $manager,Request $request)
    {
        $booking = new Booking();

        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user = $this->getUser();

            $booking->setBooker($user)
                    ->setAd($ad);

            // si les dates ne sont pas disponibles  alors retourner un message d'erreur

                if(!$booking->isBookableDates()){
                    $this->addFlash(
                        'warning',
                        "les dates choisies ne sont pas disponibles pour l'instant"
                    );
                }else{
            // sinon enregistrer la reservation
                        $manager->persist($booking);
                        $manager->flush();

                        return $this->redirectToRoute('booking_show', [
                            'id' => $booking->getId(),
                            'withAlert'=> true
                        ]);
                }            
        }


        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * permet d'afficher une reservation
     * 
     * @Route("/booking/{id}", name="booking_show")
     * @param Resquest $request
     * @param ObjectManager $manager
     * @param Booking $booking
     * @return Response
     */
    public function show(Booking $booking,Request $request, ObjectManager $manager){

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid()){

            $comment->setAd($booking->getAd())
                    ->setAuthor($this->getUser());


            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre avis a bien été pris en compte"
            );
        }

        return $this->render('booking/show.html.twig',[
            'booking' => $booking,
            'form'    => $form->createView()
        ]);

    }
}
