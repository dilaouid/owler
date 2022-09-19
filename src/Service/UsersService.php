<?php

namespace App\Service;

class UserService
{

    /**
     * @var string
     */
    private $login;

    private function parse_login($start, $firstname, $lastname) {
        $prefix = substr($firstname, 0, $start + 1);
        $login = substr($prefix . $lastname, 0, 10);
        return strtolower($login);
    }

    private function countUserWithUsername($mysql) {
        $query = $mysql->query("SELECT COUNT(login) AS 'count' FROM users WHERE login = '". $this->login ."'");
        $query->execute();
        $rows = $query->fetch(\PDO::FETCH_ASSOC);
        return $rows['count'];
    }

    public function define_login($mysql, $firstname, $lastname) {
        $idx = 0;
        $this->login = $this->parse_login($idx, $firstname, $lastname);
        $suffix = 1;
        $prefixIdx = 1;

        // countUserWithUsername is a function which count the user with this
        // specific username. You have to do it yourself, according to the specificities
        // of your tables
        while ($this->countUserWithUsername($mysql) > 0) {
            if ($idx >= strlen($firstname)) {
                if (strlen($firstname) >= $prefixIdx - 1) {
                    $prefixIdx = 1;
                    $suffix = 1;
                }
                $this->login = $this->parse_login($prefixIdx - 1, $firstname, $lastname) . $suffix;
                $prefixIdx++;
            } else
                $this->login = $this->parse_login($idx++, $firstname, $lastname);
        }
        return ($this->login);
    }

}