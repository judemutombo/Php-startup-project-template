<?php 

namespace App\Auth;

use App;
use App\Database\Database;


class SIGNIN_CASE{
    const SIGNED = 1;
    const NOUSER = 2;
    const WRONGPASSWORD = 3;

};

class Auth{ 
    //a basic auth class in case we need one in the project (singleton)
    //you can rewrite the class but do not delete $_instance, $db, $connect variables, they are useful for proper functioning of the class

    private static $_instance = null;

    private Database $db;
    private $connected = false;
    private $name;

    public static function getAuth(Database $db)
    {
        if(is_null(self::$_instance))
        {
            $_instance = new Auth($db);
        }
        return $_instance;
    }
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function signup($_name, $_mail, $_pass) : bool//to rewrite on your own
    {
        $temppass = password_hash($_pass,PASSWORD_DEFAULT);
        do{
            $id = $this->generate_id();
            $clause = array(
                array(
                    "column" => "user_id",
                    "condition" => "="
                ),
            );
            $result = $this->db->select("tester",[], $clause, [],[$id]);
        }while(count($result) > 0 );

        if($this->db->insert("tester", ["name", "mail", "user_id", "password"], [$_name, $_mail, $id, $temppass])){
            $_SESSION['user'] = $id;
            $_SESSION['username'] = $_name;
            $this->connected = true;
            return true;
        }else{
            return false;
        }

    }

    public function signin($_mail,$_pass) : int //to rewrite on your own
    {
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
                $_SESSION['user'] = $result[0]->user_id;
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

    public function logout(){
        unset($_SESSION['user']);
        unset($_SESSION['username']);
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
    public function isConnect()
    {
        return isset($_SESSION['user']);
    }
}

