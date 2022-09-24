<?php

namespace App\Service;

class UserService
{

    /**
     * @var string
     */
    private $username;

    private function parse_username($start, $firstname, $lastname) {
        $prefix = substr($firstname, 0, $start + 1);
        $username = substr($prefix . $lastname, 0, 10);
        return strtolower($username);
    }

    private function countUserWithUsername(\PDO $mysql) {
        $query = $mysql->query("SELECT COUNT(username) AS 'count' FROM user WHERE username = '". $this->username ."'");
        $query->execute();
        $rows = $query->fetch(\PDO::FETCH_ASSOC);
        return $rows['count'];
    }

    public function define_username(\PDO $mysql, string $firstname, string $lastname) {
        $idx = 0;
        $this->username = $this->parse_username($idx, $firstname, $lastname);
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
                $this->username = $this->parse_username($prefixIdx - 1, $firstname, $lastname) . $suffix;
                $prefixIdx++;
            } else
                $this->username = $this->parse_username($idx++, $firstname, $lastname);
        }
        return ($this->username);
    }

}