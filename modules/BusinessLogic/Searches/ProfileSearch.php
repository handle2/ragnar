<?php
/**
 * Created by PhpStorm.
 * User: Krisz
 * Date: 2016.12.07.
 * Time: 21:21
 */

namespace Modules\BusinessLogic\Search;


use Modules\BusinessLogic\ContentSettings\Profile;
use Modules\BusinessLogic\Models\Profiles;

class ProfileSearch extends BaseSearch
{
    public $id;
    
    public $username;
    
    public $password;
    
    public $email;
    
    public $name;
    
    public $role;
    
    public $group;

    public $availableRoles;

    /**
     * @return ProfileSearch
     */
    public static function createProfileSearch(){

        $search = new ProfileSearch();
        $search->model = new Profiles();
        /**@var \Modules\BusinessLogic\ContentSettings\Profile $object*/
        $search->object = new Profile();
        return $search;
    }

    /**
     * @return array
     */
    public function _readSearch(){
        $params = parent::_readSearch();

        if($this->email){
            $params['email'] = $this->email;
        }
        if($this->username){
            $params['username'] = $this->username;
        }

        if($this->password){
            $params['password'] = $this->password;
        }

        if($this->name){
            $params['name'] = $this->name;
        }

        if($this->availableRoles && !empty($this->availableRoles)){
            
            $params['role'] =  array('$in' => $this->availableRoles);
        }

        if($this->role){
            $params['role'] = $this->role;
        }
        if($this->group){
            $params['group'] = $this->group;
        }
        
        return $params;
    }
}