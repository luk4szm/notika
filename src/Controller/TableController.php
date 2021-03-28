<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Table;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TableController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/table/{slug}", name="season_table")
     */
    public function table(Season $season): Response
    {

        $table = $this->em->getRepository(Table::class)->findAll();

        dump($season, $table);

        return $this->render('table/table.html.twig', [
            'season' => $season,
            'table' => $table,
        ]);
    }
}
