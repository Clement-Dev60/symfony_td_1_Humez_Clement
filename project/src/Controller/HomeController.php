<?php
namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\NewsRepository;
use App\Repository\PromoRepository;
use App\Service\RssService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route("/", name: "app_home")]
    public function index(
        EventRepository $eventRepository,
        NewsRepository $newsRepository,
        PromoRepository $promoRepository,
        RssService $rssService,
    ): Response {
        $events = $eventRepository->findAll();
        $newsList = $newsRepository->findBy(["active" => true]);
        $rssItems = $rssService->fetch("https://www.agglo-compiegne.fr/rss/actualites");

        $schedule = [];
        foreach ($events as $event) {
            $promoName = $event->getPromo()->getName();
            $day = $event->getStartDate()->format("Y-m-d");
            $schedule[$promoName][$day][] = $event;
        }

        return $this->render("home/index.html.twig", [
            "schedule" => $schedule,
            "newsList" => $newsList,
            "rssItems" => $rssItems,
            "promos"   => $promoRepository->findAll(),
        ]);
    }
}