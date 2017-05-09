<?php
/**
 * Created by PhpStorm.
 * User: Krisz
 * Date: 2016.11.14.
 * Time: 14:23
 */

namespace Modules\BusinessLogic\ContentSettings;
use Modules\BusinessLogic\Models as Models;

class Profile extends Base
{
    public $id;
    
    public $username;
    
    public $password;
    
    public $role;
    
    public $group;

    /**
     * @param $obj
     * @return Profile
     */
    public function generate($obj){
        $profile = new Profile();
        $profile->id = $obj->id;
        $profile->username = $obj->username;
        $profile->password = !empty($obj->password)?$obj->password:null;
        $profile->role = $obj->role;
        $profile->group = $obj->group;

        return $profile;
    }

    /**
     * @param $username
     * @param $password
     * @return bool|Profile
     */
    public static function login($username, $password){
        $cp = new Profile();
        $mp = new Models\Profiles();
        $profile = $mp->loginProfile($username, $password);
        if($profile){
            return $cp->generate($profile);
        }
        return false;

    }

    /**
     * Törlés
     * @return bool
     */
    public function delete(){

        $this->deleteCache($this);
        $this->deleteDocuments();
        $model = new Models\Profiles();
        $profile = $model->create($this->id);
        if($profile->delete()){
            unset($this);
            return true;
        }else{
            return false;
        }
    }

    /**
     * Mentés
     * @return bool
     */
    public function save(){

        $this->deleteCache($this);

        $model = new Models\Profiles();
        /**@var Models\Profiles $profile*/
        $profile = $model->create($this->id);
        $profile->id = $this->id;
        $profile->username = $this->username;
        $profile->password = $this->password;
        $profile->role = $this->role;
        $profile->group = $this->group;
        if($profile->save()){
            return true;
        }else{
            return false;
        }
    }

    public function setPassword($password){
        $model = new Models\Profiles();
        /**@var Models\Profiles $profile*/
        $profile = $model->create($this->id);
        $profile->password = $password;
        if($profile->save()){
            return true;
        }else{
            return false;
        }
    }

}