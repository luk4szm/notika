<?php

namespace App\Controller;

use App\Entity\User;
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
			->find(['id' => $this->getUser()]);

		return $this->render('profile/index.html.twig', [
			'controller_name' => 'UserController',
			'user_profile' => $user
		]);
	}
}
