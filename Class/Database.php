<?php

    class Database {
        public $mysql;

        private $host;
        private $password;
        private $username;
        private $port;
        private $dbname;

        function __construct($host, $password, $username, $dbname, $port = 3306) {
            $this->host = $host;
            $this->password = $password;
            $this->username = $username;
            $this->dbname = $dbname;
            $this->port = $port;
        }

        private function checkDatabaseConfiguration() {
            $configDatas = [$this->host, $this->username, $this->dbname];
            array_walk($configDatas, function($el, $i) {
                if (strlen($el) == 0)
                    throw new Exception("Le fichier de configuration est invalide. Clé manquante: " . $i);
            });
        }

        public function init() {
            try {
                $this->checkDatabaseConfiguration();
                $this->mysql = new PDO('mysql:host='. $this->host .';port=' . $this->port . ';dbname=' . $this->dbname  . ';charset=utf8', $this->username, $this->username);
            } catch (Exception $e) {
                die("Une erreur est survenue: " . $e->getMessage());
            }
        }
    }

?>