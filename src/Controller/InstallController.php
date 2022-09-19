<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class InstallController extends AbstractController
{

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var Array
     */
    public $err;

    /**
     * @var bool
     */
    public $success;

    /**
     * @var ParameterBag
     */
    public $request;


    public function __construct($twig) {
        require_once (dirname(__DIR__).'/utils/response.php'); 
        $this->twig = $twig;
    }

    public function index(): Response {
        return new Response($this->twig->render('pages/install/home.html.twig'));
    }

    private function databaseVerification(): bool {
        
        $host = $this->request->get('host');
        $username = $this->request->get('mysql-username');
        $password = $this->request->get('mysql-password');
        $port = $this->request->get('port', 3306);

        try {
            new \PDO('mysql:host='. $host .';port=' . $port . ';dbname=' . null  . ';charset=utf8', $username, $password);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function platformVerification(): void {
        $name = $this->request->get('platform_name');
        $this->success = strlen($name) >= 3 && strlen($name) <= 20;
    }

    private function adminVerification(): void {
        $email = $this->request->get("admin_email");
        $password = $this->request->get("admin_password");
        $confirm = $this->request->get("confirm_admin_password");
        $firstname = $this->request->get('admin_firstname');
        $lastname = $this->request->get('admin_lastname');

        // Between 8 and 20 characters, including a lowercase, uppercase, digit and symbol
        $passwordPattern = '$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            array_push($this->err, ["admin_email" => "L'adresse email saisit est invalide"]);
        if (!$lastname)
            array_push($this->err, ["admin_lastname" => "Le nom de famille doit être renseigné"]);
        if (!$firstname)
            array_push($this->err, ["admin_firstname" => "Le prénom doit être renseigné"]);

        // Check for password pattern and confirmation
        if (!preg_match($passwordPattern, $password))
            array_push($this->err, ["admin_password" => "Le mot de passe doit contenir au moins 8 caractères, une minuscule, une majuscule, un symbole et un chiffre"]);
        else if ($confirm !== $password)
            array_push($this->err, ["confirm_admin_password" => "Les mots de passes saisits ne sont pas identiques"]);
    }

    public function database(Request $req): Response {

        $this->request = $req->request;

        if ($this->databaseVerification())
            return new Response(setResponse(200, "OK", []), 200);
        return new Response(setResponse(406, "Les données de connexion à la base de données sont invalides", ["host", "mysql-username", "mysql-password"]), 406);
    }

    public function platform(Request $req): Response {
        $this->request = $req->request;

        $this->platformVerification();
        $statusCode = $this->success ? 200 : 406;

        return new Response(setResponse($statusCode, $this->success ? "OK" : "Le nom de votre plateforme de connexion doit faire entre 3 et 20 caractères.", $this->success ? [] : ['platform_name']), $statusCode);
    }

    public function admin(Request $req): Response {
        $this->request = $req->request;

        $this->adminVerification();
        
        $this->success = count($this->err) === 0;
        $statusCode = $this->success ? 200 : 406;
        return new Response(setResponse(
            $statusCode,
            $this->success ? 'OK' : "Le formulaire saisit est incorrect.",
            $this->err,
        ), $statusCode);
    }
}