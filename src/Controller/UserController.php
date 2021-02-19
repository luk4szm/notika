<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="users")
	 * @return Response
	 * @IsGranted("ROLE_USER")
     */
    public function index(): Response
    {
    	$em = $this->getDoctrine()->getManager();
    	$users = $em->getRepository(User::class)->findAll();

    	dump($users);

        return $this->render('user/index.html.twig', [
			'users' => $users
        ]);
    }
}
