<?php

namespace App\DataFixtures;

use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TeamFixtures extends Fixture
{
	public function load(ObjectManager $manager)
	{
		$team = new Team();
		$team->setName('Lech Poznań');
		$team->setShortName('LPO');
		$team->setIsClub(true);
		$team->setCity('Poznań');

		$manager->persist($team);
		$manager->flush();
	}
}
