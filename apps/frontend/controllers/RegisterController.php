<?php
/**
 * Created by PhpStorm.
 * User: Krisz
 * Date: 2017.05.01.
 * Time: 16:21
 */

namespace Multiple\Frontend\Controllers;


use Modules\BusinessLogic\ContentSettings\Profile;
use Modules\BusinessLogic\Search\ProfileSearch;

class RegisterController extends ControllerBase
{
    public function indexAction(){
        
    }
    
    public function sendAction(){
        $form = $this->request->getJsonRawBody();

        $search = ProfileSearch::createProfileSearch();
        /** @var Profile $profile */
        $profile = $search->create();
        $profile->username = $form->username;
        $profile->password = $form->password;
        $profile->save();
        return $this->api('200',$profile);
    }
}