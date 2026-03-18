<?php
namespace App\Controller;

use App\Entity\Event;
use App\Entity\News;
use App\Entity\Promo;
use App\Form\EventFormType;
use App\Form\NewsFormType;
use App\Form\PromoFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("IS_AUTHENTICATED_FULLY")]
class AdminController extends AbstractController
{
    #[Route("/admin", name: "admin")]
    public function home(Request $request, EntityManagerInterface $em): Response
    {
        $news = new News();
        $newsForm = $this->createForm(NewsFormType::class, $news, [
            'csrf_token_id' => 'news_form',
        ]);
        $newsForm->handleRequest($request);
        if ($newsForm->isSubmitted() && $newsForm->isValid()) {
            $em->persist($news);
            $em->flush();
            $this->addFlash("success", "News créée !");
            return $this->redirectToRoute("admin");
        }

        $event = new Event();
        $eventForm = $this->createForm(EventFormType::class, $event, [
            'csrf_token_id' => 'event_form',
        ]);
        $eventForm->handleRequest($request);
        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $em->persist($event);
            $em->flush();
            $this->addFlash("success", "Événement créé !");
            return $this->redirectToRoute("admin");
        }

        $promo = new Promo();
        $promoForm = $this->createForm(PromoFormType::class, $promo, [
            'csrf_token_id' => 'promo_form',
        ]);
        $promoForm->handleRequest($request);
        if ($promoForm->isSubmitted() && $promoForm->isValid()) {
            $em->persist($promo);
            $em->flush();
            $this->addFlash("success", "Promo créée !");
            return $this->redirectToRoute("admin");
        }
        return $this->render("admin/forms.html.twig", [
            "newsForm" => $newsForm,
            "eventForm" => $eventForm,
            "promoForm" => $promoForm,
        ]);
    }
}
