<?php

namespace App\Controller;

use App\Entity\Users;
use App\Service\UserService;
use App\Service\RequestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    public function loginPage(): Response {
        $selectedPictures = $this->getAllSideImagesFiles();
        return new Response($this->twig->render('pages/auth/login.html.twig', [
            "selected_picture" => $selectedPictures[array_rand($selectedPictures)],
            "platform_name" => "owler"
        ]));
    }

}