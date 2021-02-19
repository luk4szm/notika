<?php

namespace App\DataFixtures;

use App\Entity\Game;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GameFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
		$game = new Game();
		$game->setIsCounted(true);
		$game->setDate(\DateTime::ATOM);
		$game->setCreatedAt(\DateTime::ATOM);
		$game->setCreatedBy(1);
		$game->setRound(1);
		$game->setIsAwarded(true);
		$manager->persist($game);

        $manager->flush();
    }
}
