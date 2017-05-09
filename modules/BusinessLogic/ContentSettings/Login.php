<?php
/**
 * Created by PhpStorm.
 * User: Krisz
 * Date: 2016.11.13.
 * Time: 22:11
 */

namespace Modules\BusinessLogic\ContentSettings;
use Modules\BusinessLogic\Models as Models;

class Login extends Base
{
    public $id;
    public $hash;
    public $time;
    public $userId;


    private function generateLogin($t){
        $login = new Login();
        $login->id = $t->id;
        $login->hash = $t->hash;
        $login->time = $t->time;
        $login->userId = $t->userId;
        return $login;
    }

    public static function createLogin($info){
        $cs = new Login();
        $loginModel = new Models\Login();
        $login = $loginModel->findFirst(['conditions'=>['ip'=>$info['ip'],'hash'=>$info['hash']]]);
        if(!$login){
            $login = $loginModel->create();
        }
        $login->hash = $info['hash'];
        $login->ip = $info['ip'];
        $login->userId = $info['id'];
        $date = new \DateTime();
        $date->modify('+7 day');
        $login->time = $date->getTimestamp();
        $login->save();
        return $cs->generateLogin($login);
    }

    public static function getLogin($hash){
        if(!$hash){
            return false;
        }
        $cs = new Login();
        $loginModel = new Models\Login();
        $l = $loginModel->create($hash);
        if(!$l){
            return false;
        }
        return $cs->generateLogin($l);
    }

    public function delete(){
        $loginModel = new Models\Login();
        $login = $loginModel->create($this->hash);
        $login->delete();
    }


}