<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use App\Service\RequestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class InstallController extends AbstractController
{

    /**
     * @var string
     */
    private $username;

    private $mysql;

    /**
     * @var RequestService
     */
    private $rs;

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
        
        $host = $this->rs->check_key('host');
        $username = $this->rs->check_key('mysql-username');
        $password = $this->rs->check_key('mysql-password');
        $port = $this->rs->check_key('port', 3306);

        try {
            $this->mysql = new \PDO('mysql:host='. $host .';port=' . $port . ';dbname=' . null  . ';charset=utf8', $username, $password);
            return true;
        } catch (\Exception $e) {
            array_push($this->data, "host", "mysql-username", "mysql-password");
            return false;
        }
    }

    private function platformVerification(): void {
        $name = $this->rs->check_key('platform_name');
        if (strlen($name) < 3 || strlen($name) > 20)
            array_push($this->data, ["platform_name" => "Le nom de votre plateforme de connexion doit faire entre 3 et 20 caractères."]);
    }

    private function adminVerification(): void {
        $email = $this->rs->check_key("admin_email");
        $password = $this->rs->check_key("admin_password");
        $confirm = $this->rs->check_key("confirm_admin_password");
        $firstname = $this->rs->check_key('admin_firstname');
        $lastname = $this->rs->check_key('admin_lastname');

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
            if (!$this->rs->check_key("condition_$i", false))
                array_push($this->data, "condition_$i");
        }
    }

    private function createAdmin(): void {
        $admin = new User();
        $userService = new UserService();
        $this->username = $userService->define_username($this->mysql, $this->rs->check_key('admin_firstname'), $this->rs->check_key('admin_lastname'));
        $admin->setRoles(['admin'])
            ->setEmail($this->rs->check_key('admin_email'))
            ->setFirstname($this->rs->check_key('admin_firstname'))
            ->setLastname($this->rs->check_key('admin_lastname'))
            ->setUsername($this->username)
            ->setPassword(password_hash($this->rs->check_key('admin_password'), PASSWORD_DEFAULT));
        $em = $this->getDoctrine()->getManager();
        $em->persist($admin);
        $em->flush();
    }

    public function database(Request $req): Response {
        $this->rs = new RequestService($req->request);
        $this->all = $req->request->all();

        if ($this->databaseVerification())
            return new Response(setResponse("OK", $this->data), 200);
        return new Response(setResponse("Les données de connexion à la base de données sont invalides", $this->data), 406);
    }

    public function platform(Request $req): Response {
        $this->rs = new RequestService($req->request);
        $this->all = $req->request->all();

        $this->platformVerification();
        $this->checkSuccess();

        $statusCode = $this->success ? 200 : 406;

        return new Response(setResponse($this->success ? "OK" : "Le nom de votre plateforme de connexion doit faire entre 3 et 20 caractères.", $this->data), $statusCode);
    }

    public function admin(Request $req): Response {
        $this->rs = new RequestService($req->request);
        $this->all = $req->request->all();

        $this->adminVerification();
        $this->checkSuccess();
        
        $statusCode = $this->success ? 200 : 406;
        return new Response(setResponse(
            $this->success ? 'OK' : "Le formulaire saisit est incorrect.",
            $this->data,
        ), $statusCode);
    }

    public function install(Request $req): Response {
        $this->rs = new RequestService($req->request);
        $this->all = $this->rs->all();

        $this->platformVerification();
        $this->cguVerification();
        $this->adminVerification();
        $this->databaseVerification();
        $this->checkSuccess();

        // write env file and inject sql
        if ($this->success) {
            $host = $this->rs->check_key('host');
            $username = $this->rs->check_key('mysql-username');
            $password = $this->rs->check_key('mysql-password');
            $port = $this->rs->check_key('port', "3306");

            $db = $_ENV["DATABASE_URL"];
            if (file_exists("../.env")) {

                $url = "DATABASE_URL=\"mysql://".$username.":".$password."@".$host.":".(strlen($port) > 0 ? $port : "3306" )."/owler\"";
                file_put_contents("../.env", str_replace(
                    "DATABASE_URL=\"$db\"", $url, file_get_contents('../.env')
                ));

                $envPlatformName = $_ENV['PLATFORM_NAME'];
                $newPlatformName = "PLATFORM_NAME=\"".$this->rs->check_key("platform_name")."\"";
                file_put_contents("../.env", str_replace(
                    "PLATFORM_NAME=\"$envPlatformName\"", $newPlatformName, file_get_contents('../.env')
                ));

                $inputPlatformDesc = $this->rs->check_key("platform_description");
                if ($inputPlatformDesc) {
                    $envPlatformDesc = $_ENV['PLATFORM_DESC'];
                    $newPlatformDesc = "PLATFORM_DESC=\"$inputPlatformDesc\"";
                    file_put_contents("../.env", str_replace(
                        "PLATFORM_DESC=\"$envPlatformDesc\"", $newPlatformDesc, file_get_contents('../.env')
                    ));
                }
                
                $this->mysql->query('CREATE DATABASE IF NOT EXISTS owler CHARACTER SET utf8 COLLATE utf8_unicode_ci;');
                $this->mysql = new \PDO('mysql:host='. $host .';port=' . $port . ';dbname=owler;charset=utf8', $username, $password);
                $this->createAdmin();
                $this->data = $this->username;
            }
        }
        $statusCode = $this->success ? 200 : 406;

        return new Response(setResponse(
            $this->success ? 'OK' : "Tout les champs doivent être correctement remplis.",
            $this->data,
        ), $statusCode);
    }
}