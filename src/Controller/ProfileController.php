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
     * @IsGranted("ROLE_USER")
	 */
	public function summary(): Response
	{
        /** @var User $user */
        $user = $this->getUser();

		return $this->render('profile/content/summary.html.twig', [
			'user' => $user
		]);
	}
}
