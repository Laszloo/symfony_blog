<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController {

    /**
     * @Route("/login", methods={"GET", "POST"})
     */
    public function index(AuthenticationUtils $authenticationUtils, UserInterface $userInterface = null) {
    
        if (!$userInterface) {
            $error = $authenticationUtils->getLastAuthenticationError();
            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render('admin/login.html.twig', [
                'last_username' => $lastUsername,
                'error'         => $error,
            ]);
        }
        return $this->redirect("/");
    }


    /**
     * @Route("/create", methods={"GET"})
     */
    public function create() {

        if (!$this->getDoctrine()->getRepository(User::class)->findOneBy(["email" => "info@admin.hu"])) {
            
            $user = new User();
            $user->setEmail("info@admin.hu")
                ->setPassword('$argon2id$v=19$m=65536,t=4,p=1$rlY6edXLbpe0LNOyIHKcrg$unv735SfA7Svwx0u0hmMz2ODebKztI/lib/FMRP5bTU')
                ->setRoles($user->getRoles());

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->redirect("/login");
    }

    /**
     * @Route("/logout", methods={"GET"})
     */
    public function logout(): void
    {
        
    }
}
