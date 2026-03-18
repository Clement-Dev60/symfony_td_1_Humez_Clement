<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\PromoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CalendarController extends AbstractController
{
    #[Route('/calendar', name: 'app_calendar')]
    public function index(PromoRepository $promoRepo): Response
    {
        return $this->render('calendar/index.html.twig', [
            'promos' => $promoRepo->findAll(),
        ]);
    }

    #[Route('/api/events', name: 'api_events', methods: ['GET'])]
    public function apiEvents(Request $request, EventRepository $repo): JsonResponse
    {
        $start   = new \DateTime($request->query->get('start'));
        $end     = new \DateTime($request->query->get('end'));
        $promoId = $request->query->get('promoId');

        $events = $repo->findByPeriodAndPromoId($start, $end, $promoId);

        $data = array_map(fn($e) => [
            'id'    => $e->getId(),
            'title' => $e->getTitle(),
            'start' => $e->getStartDate()->format(\DateTime::ATOM),
            'end'   => $e->getEndDate()?->format(\DateTime::ATOM),
            'extendedProps' => [
                'room'    => $e->getRoom(),
                'speaker' => $e->getSpeaker(),
            ],
        ], $events);

        return $this->json($data);
    }
}
