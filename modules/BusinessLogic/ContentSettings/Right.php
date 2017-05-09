<?php
/**
 * Created by PhpStorm.
 * User: Krisz
 * Date: 2016.11.27.
 * Time: 22:30
 */

namespace Modules\BusinessLogic\ContentSettings;

use Modules\BusinessLogic\Models as Models;
use Phalcon\Mvc\Collection;

class Right extends Base
{
    public $id;
    
    public $name;
    
    public $code;
    
    public $type;
    
    public $parent;
    
    public $actions = [];
    
    public $langs;

    /**
     * @param Models\Rights $obj
     * @param $lang
     * @return Right
     */
    public function generate(Models\Rights $obj,$lang){
        $right = new Right();
        $right->id = $obj->id;
        $langs = (object)$obj->langs;
        $right->name = isset($langs->{$lang}['name'])?$langs->{$lang}['name']:$obj->name;
        $right->parent = isset($langs->{$lang}['parent'])?$langs->{$lang}['parent']:$obj->parent;
        $right->code = isset($langs->{$lang}['code'])?$langs->{$lang}['code']:$obj->code;
        $right->type = isset($langs->{$lang}['type'])?$langs->{$lang}['type']:$obj->type;
        $right->actions = isset($langs->{$lang}['actions'])?$langs->{$lang}['actions']:$obj->actions;
        $right->langs = $obj->langs;
        return $right;
    }

    /**
     * @return bool
     */
    public function delete(){

        $this->deleteCache($this);
        
        $model = new Models\Rights();
        $right = $model->create($this->id);
        if($right->type == "group"){
            $wole = $model->find(array('conditions'=>["parent"=>$right->code]));
            foreach ($wole as $w){
                /**@var Right $w */
                $w->delete();
            }
        }
        if($right->delete()){
            unset($this);
            return true;
        }else{
            return false;
        }
    }

    /**
     * @return bool
     */
    public function save(){

        $this->deleteCache($this);
        
        $model = new Models\Rights();
        $right = $model->create($this->id);
        $right->id = $this->id;
        $right->name = $this->name;
        $right->parent = $this->parent;
        $right->code = $this->code;
        $right->type = $this->type;
        $right->actions = $this->actions;
        $right->langs = $this->langs;
        if($right->save()){
            return true;
        }else{
            return false;
        }
    }
}