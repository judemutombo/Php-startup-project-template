<?php 

namespace App\Auth;

use App;
use App\Database\Database;
use App\Singleton\singletonDbTrait;

class SIGNIN_CASE{
    const SIGNED = 1;
    const NOUSER = 2;
    const WRONGPASSWORD = 3;

};

class SIGNUP_CASE{
    const SIGNED = 1;
    const EXITEDMAIL = 2;
    const ERROR = 3;
};

class Auth{ 
    //a basic auth class in case we need one in the project (singleton)
    //you can rewrite the class but do not delete  $db and $connect variables, they are useful for proper functioning of the class

    private static $_instance = null;

    private Database $db;
    private $connected = false;
    private $name;
    private $user;

    use singletonDbTrait;

    private function __construct(Database $db) //to rewrite on your own
    {
        $this->db = $db;
    }

    public function signup($_name, $_mail, $_pass) : int //to rewrite on your own
    {
        $temppass = password_hash($_pass,PASSWORD_DEFAULT);

        $clause = array(
            array(
                "column" => "mail",
                "condition" => "="
            ),
        );
        
        $result = $this->db->select("tester",[], $clause, [],[$_mail]);
        if(count($result) > 0){
            return SIGNUP_CASE::EXITEDMAIL;
        }

        do{
            $id = $this->generate_id();
            $clause = array(
                array(
                    "column" => "user_id",
                    "condition" => "="
                ),
                array(
                    "column" => "mail",
                    "condition" => "="
                ),
            );
            $result = $this->db->select("tester",[], $clause, ["OR"],[$id, $_mail]);
        }while(count($result) > 0 );

        if($this->db->insert("tester", ["name", "mail", "user_id", "password"], [$_name, $_mail, $id, $temppass])){
            $this->user = $id;
            $_SESSION['username'] = $_name;
            $this->connected = true;
            return SIGNUP_CASE::SIGNED;
        }else{
            return SIGNUP_CASE::ERROR;
        }

    }

    public function signin($_mail,$_pass) : int //to rewrite on your own
    {
        $this->logout();
        $clause = array(
            array(
                "column" => "mail",
                "condition" => "="
            ),
        );
        $result = $this->db->select("tester",[], $clause, [],[$_mail]);
        if(count($result) == 1)
        {
            if(password_verify($_pass,$result[0]->password))
            {
                $this->user = $result[0]->user_id;
                $_SESSION['username'] = $result[0]->name;
                $this->connected = true;
                return SIGNIN_CASE::SIGNED;
            }
            else{
                return SIGNIN_CASE::WRONGPASSWORD;
            }
        }
        else{
            return  SIGNIN_CASE::NOUSER;
        }
    }

    public function logout() : void //to rewrite on your own
    {
        $this->user = null;
        if(isset($_SESSION['username'])) unset($_SESSION['username']);

        $this->connected = false;
    }

    public function generate_id() : string //to rewrite on your own
    {
            $aleatoire = 0;
            $dec='A'; 
            $de = array();
            for ($i = 0; $i < 15; $i++)
            {
                $aleatoire = rand(0,35);
                if($aleatoire >=26)
                {
                    $aleatoire =35 - $aleatoire;
                    switch ($aleatoire)
                    {
                        case 0:
                            $de[$i]='0';
                        case 1:
                            $de[$i]='1';
                            break;
                        case 2:
                            $de[$i]='2';
                            break;
                        case 3:
                            $de[$i]='3';
                            break;
                        case 4:
                            $de[$i]='4';
                            break;
                        case 5:
                            $de[$i]='5';
                            break;
                        case 6:
                            $de[$i]='6';
                            break;
                        case 7:
                            $de[$i]='7';
                            break;
                        case 8:
                            $de[$i]='8';
                            break;
                        case 9:
                            $de[$i]='9';
                            break;
                    }
                }
                else{
                    for ($j=0; $j < $aleatoire; $j++) { 
                        $dec= ++$dec;
                    }
                    $de[$i] = $dec;
                }
                $dec='A'; 
            }

        return implode($de);
    }

    public function isConnect() : bool //to rewrite on your own
    {
        return isset($_SESSION['username']) & $this->user != null;
    }


}

