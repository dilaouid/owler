<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class InstallController extends AbstractController
{

    private $mysql;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var Array
     */
    public $data = [];

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
            $this->mysql = new \PDO('mysql:host='. $host .';port=' . $port . ';dbname=' . null  . ';charset=utf8', $username, $password);
            return true;
        } catch (\Exception $e) {
            array_push($this->data, ["host", "mysql-username", "mysql-password"]);
            return false;
        }
    }

    private function platformVerification(): void {
        $name = $this->request->get('platform_name');
        if (strlen($name) < 3 && strlen($name) > 20)
            array_push($this->data, ["platform_name" => "Le nom de votre plateforme de connexion doit faire entre 3 et 20 caractères."]);
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
            array_push($this->data, ["admin_email" => "L'adresse email saisit est invalide"]);
        if (!$lastname)
            array_push($this->data, ["admin_lastname" => "Le nom de famille doit être renseigné"]);
        if (!$firstname)
            array_push($this->data, ["admin_firstname" => "Le prénom doit être renseigné"]);

        // Check for password pattern and confirmation
        if (!preg_match($passwordPattern, $password))
            array_push($this->data, ["admin_password" => "Le mot de passe doit contenir au moins 8 caractères, une minuscule, une majuscule, un symbole et un chiffre"]);
        else if ($confirm !== $password)
            array_push($this->data, ["confirm_admin_password" => "Les mots de passes saisits ne sont pas identiques"]);
    }

    private function checkSuccess(): void {
        $this->success = count($this->data) === 0;
    }

    private function cguVerification(): void {
        for ($i=1; $i < 5; $i++) { 
            if (!$this->request->get("condition_$i"))
                array_push($this->data, "condition_$i");
        }
    }

    public function database(Request $req): Response {

        $this->request = $req->request;

        if ($this->databaseVerification())
            return new Response(setResponse(200, "OK", $this->data), 200);
        return new Response(setResponse(406, "Les données de connexion à la base de données sont invalides", $this->data), 406);
    }

    public function platform(Request $req): Response {
        $this->request = $req->request;

        $this->platformVerification();
        $this->checkSuccess();

        $statusCode = $this->success ? 200 : 406;

        return new Response(setResponse($statusCode, $this->success ? "OK" : "Le nom de votre plateforme de connexion doit faire entre 3 et 20 caractères.", $this->data), $statusCode);
    }

    public function admin(Request $req): Response {
        $this->request = $req->request;

        $this->adminVerification();
        $this->checkSuccess();
        
        $statusCode = $this->success ? 200 : 406;
        return new Response(setResponse(
            $statusCode,
            $this->success ? 'OK' : "Le formulaire saisit est incorrect.",
            $this->data,
        ), $statusCode);
    }

    public function install(Request $req): Response {
        $this->request = $req->request;

        $this->platformVerification();
        $this->cguVerification();
        $this->adminVerification();
        $this->databaseVerification();
        $this->checkSuccess();

        // write env file and inject sql
        if ($this->success) {
            $host = $this->request->get('host');
            $username = $this->request->get('mysql-username');
            $password = $this->request->get('mysql-password');
            $port = $this->request->get('port', "3306") ?? "3306";

            $db = $_ENV["DATABASE_URL"];
            if (file_exists("../.env")) {
                $url = "DATABASE_URL=\"mysql://".$username.":".$password."@".$host.":".(strlen($port) > 0 ? $port : "3306" )."/owler?serverVersion=14&charset=utf8mb4\"";
                file_put_contents("../.env", str_replace(
                    "DATABASE_URL=\"$db\"", $url, file_get_contents('../.env')
                ));
                $this->mysql->query('CREATE DATABASE owler CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
            }
        }
        $statusCode = $this->success ? 200 : 406;

        return new Response(setResponse(
            $statusCode,
            $this->success ? 'OK' : "Tout les champs doivent être correctement remplis.",
            $this->data,
        ), $statusCode);
    }
}