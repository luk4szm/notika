<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
	private $passwordEncoder;

	public function __construct(UserPasswordEncoderInterface $passwordEncoder)
	{
		$this->passwordEncoder = $passwordEncoder;
	}

	public function load(ObjectManager $manager)
	{
		$user = new User();
		$user->setUsername('admin');
		$user->setEmail('lukasz@mikowski.pl');
		$user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
		$user->setPassword($this->passwordEncoder->encodePassword($user, '149532'));
		$user->setFirstName('Łukasz');
		$user->setLastName('Mikowski');
		$user->setActive(true);
		$user->setNation(39);
		$user->setLanguage('pl');
		$user->setMember(true);
		$user->setCreatedAt(new \DateTime());

		$manager->persist($user);
		$manager->flush();
	}
}
