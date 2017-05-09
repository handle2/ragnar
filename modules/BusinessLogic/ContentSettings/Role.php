<?php
/**
 * Created by PhpStorm.
 * User: Krisz
 * Date: 2016.11.26.
 * Time: 10:38
 */

namespace Modules\BusinessLogic\ContentSettings;
use Modules\BusinessLogic\Models;

class Role extends Base
{
    public $name = null;

    public $code = null;

    public $type = null;

    public $id = null;

    public $rights = null;

    public $roles = null;

    public $langs;


    /**
     * Kigenerálja az adatbázisból kiolvasott objektumot egy Role objektummá
     * @param Models\Roles $obj
     * @param $lang
     * @return Role
     */
    public function generate(Models\Roles $obj,$lang){
        $role = new Role();
        $role->id = $obj->id;
        $langs = (object)$obj->langs;
        $role->name = isset($langs->{$lang}['name'])?$langs->{$lang}['name']:$obj->name;
        $role->code = isset($langs->{$lang}['code'])?$langs->{$lang}['code']:$obj->code;
        $role->type = isset($langs->{$lang}['type'])?$langs->{$lang}['type']:$obj->type;
        $role->rights = $obj->rights?$obj->rights:[];
        $role->roles = $obj->roles?$obj->roles:[];
        $role->langs = $obj->langs;
        return $role;
    }


    /**
     * Törli az adatbázisból
     * @return bool
     */
    public function delete(){
        
        $this->deleteCache($this);

        $model = new Models\Roles();
        $role = $model->create($this->id);
        if($role->delete()){
            unset($this);
            return true;
        }else{
            return false;
        }
    }

    /**
     * Menti a módosításokat az adatbázisba
     * @return bool
     */
    public function save(){

        $this->deleteCache($this,['Profile']);

        $model = new Models\Roles();
        /** @var Models\Roles $role */
        $role = $model->create($this->id);
        $role->name = $this->name;
        $role->code = $this->code;
        $role->type = $this->type;
        $role->rights = $this->rights;
        $role->roles = $this->roles;
        $role->langs = $this->langs;
        if($role->save()){
            return true;
        }else{
            return false;
        }
    }


    
}