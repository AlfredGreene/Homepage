<?php

namespace App\Controller;

use App\Service\ChecklistService;
use App\Service\NoteService;
use Mobile_Detect;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
{
    /**
     * @var ChecklistService
     */
    protected $checklistService;

    /**
     * @var NoteService
     */
    protected $noteService;

    /**
     * @param ChecklistService $checklistService
     * @param NoteService $noteService
     */
    public function __construct(ChecklistService $checklistService, NoteService $noteService)
    {
        $this->checklistService = $checklistService;
        $this->noteService = $noteService;
    }

    /**
     * @Route("/welcome/")
     * @return Response
     */
    public function index()
    {
        $device = new Mobile_Detect();
        if ($device->isMobile() && !$device->isTablet()) {
            return $this->render('welcome/mobile.html.twig', [
                'bodyClass' => 'loading-screen'
            ]);
        }

        return $this->render('welcome/index.html.twig', [
            'bodyClass' => 'welcome',
            'notes' => $this->noteService->getForUser($this->getUser()),
            'todos' => $this->checklistService->getUncheckedItemsForUser($this->getUser())
        ]);
    }
}