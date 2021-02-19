<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
	/**
	 * @Route("/profile", name="user_profile")
	 */
	public function index(): Response
	{
		$user = $this->getDoctrine()
			->getManager()
			->getRepository(User::class)
			->findOneBy(['id' => $this->getUser()]);

		return $this->render('profile/index.html.twig', [
			'user_profile' => $user
		]);
	}
}
