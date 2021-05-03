<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile")
 * @IsGranted("ROLE_USER")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/", name="user_profile")
     */
    public function summary(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('profile/content/summary.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/password", name="profile_password")
     */
    public function password(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('profile/content/password.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/update/{id}", name="user_update", methods={"POST"})
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param User $user
     * @return JsonResponse
     */
    public function updateUserData(Request $request, EntityManagerInterface $em, User $user)
    {
        $data = json_decode($request->request->get('data'), true);

        if (isset($data['email'])) {
            $user->setLastEmail($user->getEmail());
            $user->setEmail($data['email']);
        }

        if (isset($data['firstName'])) {
            $user->setFirstName($data['firstName']);
        }

        if (isset($data['lastName'])) {
            $user->setLastName($data['lastName']);
        }

        $em->flush();

        return $this->json(['success' => 'ok']);
    }
}
