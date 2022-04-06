<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page}", name="admins_ads_index", requirements={"page": "\d+"})
     */
    public function index(AdRepository $repo, $page = 1, PaginationService $pagination): Response
    {
        $pagination->setEntityClass(Ad::class)
                ->setPage($page);


        return $this->render('admin/ad/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * form d'editioon
     * @Route("/admin/ads/{id}/edit", name="admins_ads_edit")
     * @param Ad $ad
     * @return Response
     */
    public function edit(Ad $ad, Request $request): Response {

        $form = $this->createForm(AnnonceType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();

            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()} </strong> a bien été enregistrée ! "
            );
        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * supp une annonce
     * @Route("/admin/ads/{id}/delete", name="admins_ads_delete")
     * @param Ad $ad
     * @return Response
     */
    public function delete(Ad $ad): Response {
        if(count($ad->getBookings()) > 0) {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimez l'annonce <strong>{$ad->getTitle()} </strong> 
                car elle possède déjà des réservations"
            );
        } else {
            $manager = $this->getDoctrine()->getManager();

            $manager->remove($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()} </strong> a bien été supprimée ! "
            );
        }

        return $this->redirectToRoute('admins_ads_index');
    }
}
