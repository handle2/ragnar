<?php
/**
 * Created by PhpStorm.
 * User: Krisz
 * Date: 2016.11.26.
 * Time: 10:38
 */

namespace Modules\BusinessLogic\ContentSettings;
use Modules\BusinessLogic\Models;

class Queue extends Base
{
    public $id;
    
    public $gamerID = null;

    public $waiting = 1;

    public $rank = null;


    /**
     * Kigenerálja az adatbázisból kiolvasott objektumot egy Queue objektummá
     * @param Models\Queues $obj
     * @param $lang
     * @return Role
     */
    public function generate(Models\Queues $obj,$lang){
        $item = new Queue();
        $item->id = $obj->id;
        $item->gamerID = $obj->gamerID;
        $item->waiting = $obj->waiting;
        $item->rank = $obj->rank;
        return $item;
    }


    /**
     * Törli az adatbázisból
     * @return bool
     */
    public function delete(){
        
        $this->deleteCache($this);

        $model = new Models\Queues();
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

        $model = new Models\Queues();
        /** @var Models\Queues $obj */
        $obj = $model->create($this->id);
        $obj->id = $this->id;
        $obj->gamerID = $this->gamerID;
        $obj->waiting = $this->waiting;
        $obj->rank = $this->rank;
        if($obj->save()){
            return true;
        }else{
            return false;
        }
    }


    
}