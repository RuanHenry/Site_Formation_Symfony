<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Service\PaginationService;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings/{page}", name="admins_booking_index", requirements={"page": "\d+"})
     */
    public function index(BookingRepository $repo, $page = 1, PaginationService $pagination): Response
    {
        $pagination->setEntityClass(Booking::class)
                    ->setPage($page);

        return $this->render('admin/booking/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * edit une reservation 
     * @Route("/admin/bookings/{id}/edit", name="admins_booking_edit")
     *
     * @return Response
     */
    public function edit(Booking $booking, Request $request): Response {
        $form = $this->createForm(AdminBookingType::class, $booking);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $booking->setAmount(0);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "La réservation a bien été modifiée"
            );

            return $this->redirectToRoute("admins_booking_index");
        }

        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }

    /**
     * suppprime une réservation
     * @Route("/admin/bookings/{id}/delete", name="admins_booking_delete")
     * @return Response
     */
    public function delete(Booking $booking): Response {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "La réservation a bien été supprimée"
        );

        return $this->redirectToRoute("admins_booking_index");
    }
}
