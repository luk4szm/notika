<?php

namespace App\Controller;

use App\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    /**
     * @Route("/team/{id}", name="team_detail")
     * @param int $id
     * @return Response
     */
    public function index(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $team = $em->getRepository(Team::class)->find($id);

        dump($team);

        return $this->render('team/index.html.twig', [
            'team' => $team
        ]);
    }
}
