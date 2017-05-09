<?php
/**
 * Created by PhpStorm.
 * User: Krisz
 * Date: 2016.12.07.
 * Time: 21:20
 */

namespace Modules\BusinessLogic\Search;


use Modules\BusinessLogic\Models\Login;

class LoginSearch extends BaseSearch
{
    public $id;
    
    public $hash;
    
    public $ip;
    
    public $userId;
    
    public $time;

    /**
     * @return LoginSearch
     */
    public static function createLoginSearch(){

        $search = new LoginSearch();
        $search->model = new Login();
        /**@var \Modules\BusinessLogic\ContentSettings\Login $object*/
        $search->object = new \Modules\BusinessLogic\ContentSettings\Login();
        return $search;
    }

    /**
     * @return array
     */
    public function _readSearch(){
        $params = parent::_readSearch();

        if($this->hash){
            $params['hash'] = $this->hash;
        }
        if($this->ip){
            $params['ip'] = $this->ip;
        }
        if($this->userId){
            $params['userId'] = $this->userId;
        }
        if($this->time){
            $params['time'] = $this->time;
        }

        return $params;
    }
}