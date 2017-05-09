<?php
namespace Multiple\Frontend\Controllers;

use Modules\BusinessLogic\Search\ProfileSearch;
use Modules\BusinessLogic\Search\RightSearch;
use Modules\BusinessLogic\Search\RoleSearch;
use Phalcon\Mvc\Controller;

use Modules\BusinessLogic\Models as Models;

use Modules\BusinessLogic\ContentSettings;

use Phalcon\Mvc\Dispatcher;

class ControllerBase extends Controller
{
    public $authUser;
    public $lang;


    public function beforeExecuteRoute(Dispatcher $dispatcher){
        
        $this->view->defaultLang = $this->config->defaultLanguage;
        $lang = $this->request->getHeader("XLang");

        $this->lang = !empty($lang)?$lang:$this->config->defaultLanguage;

        $result = $this->getPermission($this->router->getControllerName(),$this->router->getActionName());
        switch ($result['response']){
            case 'ok':
                $this->view->setMainView('index');
                break;
            case 'not_logged':
                if($this->router->getControllerName()!='register'){
                    //die(json_encode('not valid user'));
                }
                if(!empty($this->request->getHeader("XAuth")) && $this->router->getActionName() != 'enter'){
                    //die(json_encode('wrong hash code'));
                }
                $this->view->setMainView('login');
                break;
            case 'not_authorized':
               die('not_authorized');
        }
    }

    /**
     * @param $controller
     * @param $action
     * @return bool
     */
    public function getPermission($controller,$action){
        
        $logged = $this->isLoggedIn();
        if(!$logged){
            return array('response' => 'not_logged');
        }
        $rightSearch = RightSearch::createRightSearch();
        $rightSearch->action = $action;
        $rightSearch->controller = $controller;
        /** @var ContentSettings\Right $rootedRight */
        $rootedRight = $rightSearch->findFirst();

        $roleSearch = RoleSearch::createRoleSearch();
        $roleSearch->code = $this->authUser->role;
        /** @var ContentSettings\Role $selfRole */
        $selfRole = $roleSearch->findFirst();

        $enabledAction = true;
        if($rootedRight){
            $enabledAction = in_array($rootedRight->code,$selfRole->rights)?true:false;
        }

        if($enabledAction){
            return array('response' => 'ok');
        }else{
            return array('response' => 'not_authorized');
        }
    }
    /**
     * vizsgálja ,hogy be vagy-e lépve
     */
    public function isLoggedIn(){

        //dd($this->session->get("hash"));

        $hash = $this->request->getHeader('XAuth');

        $token = !empty($hash)?$hash:$this->session->get("hash");

        $login = ContentSettings\Login::getLogin($token);
        //dd($token);
        if($login){
            $profileSearch = ProfileSearch::createProfileSearch();
            $profileSearch->id = $login->userId;
            /** @var ContentSettings\Profile $profile */
            $profile = $profileSearch->findFirst();
            $this->authUser = new \stdClass();
            $this->authUser->id = $profile->id;
            $this->authUser->username = $profile->username;
            $this->authUser->role = $profile->role;
            /** @var \ArrayObject|ContentSettings\Document[] $pictures */
            $pictures = $profile->getPictures();

            $this->view->cover = count($pictures)>0?$pictures[0]->getUrl():false;

            $roleSearch = RoleSearch::createRoleSearch();
            $roleSearch->code = $this->authUser->role;
            /** @var ContentSettings\Role $ownRole */
            $ownRole = $roleSearch->findFirst();
            $this->authUser->availableRoles = count($ownRole->roles)>0?$ownRole->roles:[false];

        }

        return $login;
    }

    /**
     * Megvizsgálja a jogosultságokat
     * @return bool
     */
    public function hasPermission(){
        return $this->isLoggedIn();
    }

    /**
     * @param $code
     * @param $data
     * @return \Phalcon\HTTP\ResponseInterface
     */
    protected function api($code, $data)
    {

        $this->response->setStatusCode($code);
        $this->response->setContentType("application/json; charset=UTF-8");
        $this->response->setContent(json_encode($data));

        return $this->response;

    }

    public function urlMakeup($text){
        $ekezet = array('ö', 'ü', 'ó', 'ő', 'ú', 'ű', 'á', 'é', 'í');
        $normal = array('o', 'u', 'o', 'o', 'u', 'u', 'a', 'e', 'i');
        $text = $url = str_replace($ekezet, $normal, mb_strtolower($text));
        return preg_replace('/[^a-z0-9]/i', '_', $text);
    }

    public function showMeCache($justKeys = false){
        /**@var \Predis\Client $redis*/
        $keys = $this->redis->keys('*');
        if($justKeys){
            dd($keys);
        }
        foreach ($keys as $key){
            var_dump($key,json_decode($this->redis->get($key)));
        }
        die;

    }
    
}
