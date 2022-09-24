<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

class AuthController extends AbstractController
{
    /**
     * @var Environment
     */
    private $twig;

    public function __construct($twig) {
        require_once (dirname(__DIR__).'/utils/response.php'); 
        $this->twig = $twig;
    }

    private function getAllSideImagesFiles(): array {
        $path = '../public/assets/img/auth/login';
        $files = scandir($path);
        $files = array_diff(scandir($path), array('.', '..'));
        return count($files) > 0 ? $files : [];
    }

    public function login(AuthenticationUtils $authenticationUtils): Response {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error)
            $error = "Nom d'utilisateur ou mot de passe invalide.";

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $selectedPictures = $this->getAllSideImagesFiles();
        return new Response($this->twig->render('pages/auth/login.html.twig', [
            "selected_picture" => $selectedPictures[array_rand($selectedPictures)],
            "platform_name" => "owler",
            'last_username' => $lastUsername,
            'error' => $error
        ]));
    }

}