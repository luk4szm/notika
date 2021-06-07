<?php

namespace App\Controller;

use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    /**
     * @Route("/team/{slug}", name="team_detail")
     * @param Team $team
     * @return Response
     */
    public function index(Team $team): Response
    {
        return $this->render('team/team_summary.html.twig', [
            'team' => $team
        ]);
    }

    /**
     * @Route("/teams/slug-maker", name="slug_maker")
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function slugMaker(EntityManagerInterface $em): Response
    {
        $teams = $em->getRepository(Team::class)->findAll();

        foreach($teams AS $team)
        {
            $team->setSlug(null);
            $em->persist($team);
            $em->flush();
        }

        return new Response('Slug was make');
    }
}
