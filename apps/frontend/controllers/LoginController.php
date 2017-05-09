<?php
/**
 * Created by PhpStorm.
 * User: Krisz
 * Date: 2017.05.01.
 * Time: 21:39
 */

namespace Multiple\Frontend\Controllers;


use Modules\BusinessLogic\ContentSettings\Login;
use Modules\BusinessLogic\ContentSettings\Profile;
use Modules\BusinessLogic\Search\ProfileSearch;

class LoginController extends ControllerBase
{
    public function enterAction(){


        $form = $this->request->getJsonRawBody();

        $profileSearch = ProfileSearch::createProfileSearch();
        $profileSearch->lang = $this->lang;
        $profileSearch->password = $form->password;
        $profileSearch->username = $form->username;
        /** @var Profile $profile */
        $profile = $profileSearch->findFirst();

        if($profile){
            $this->cookies->set('hash',md5($profile->username),time()+3600*24*7); //1 week
            $this->session->set("hash",md5($profile->username));
            
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            $login = Login::createLogin(array('hash' => md5($profile->username),'ip'=>$ip,'id'=>$profile->id));
            return $this->api(200,($login));
        }

        return $this->api(404,'username_or_password_fail');
    }
}