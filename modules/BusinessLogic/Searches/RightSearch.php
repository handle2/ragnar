<?php
/**
 * Created by PhpStorm.
 * User: Krisz
 * Date: 2016.12.07.
 * Time: 21:21
 */

namespace Modules\BusinessLogic\Search;


use Modules\BusinessLogic\ContentSettings\Right;
use Modules\BusinessLogic\Models;

class RightSearch extends BaseSearch
{
    public $id;
   
    public $code;
   
    public $type;
   
    public $name;
   
    public $parent;

    public $controller;
    
    public $action;

    /**
     * @return RightSearch
     */
    public static function createRightSearch(){

        $search = new RightSearch();
        $search->model = new Models\Rights();
        /**@var \Modules\BusinessLogic\ContentSettings\Right $object*/
        $search->object = new Right();
        return $search;
    }

    /**
     * @return array
     */
    public function _readSearch(){
        $params = parent::_readSearch();
        //{'actions':{'$elemMatch': {'controller':'right'}}}
        if($this->action && $this->controller){
            $action = array('actions'=> array('$elemMatch'=> array('action' => $this->action)));
            $controller = array('actions'=> array('$elemMatch'=> array('controller' => $this->controller)));
            $params['$and'] = [$action,$controller];
        }
        
        if($this->type){
            $params['type'] = $this->type;
        }
        if($this->parent){
            $params['parent'] = $this->parent;
        }

        return $params;
    }
}