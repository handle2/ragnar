<?php
/**
 * Created by PhpStorm.
 * User: Krisz
 * Date: 2016.11.27.
 * Time: 22:30
 */

namespace Modules\BusinessLogic\Models;

use Modules\BusinessLogic\ContentSettings;
use Phalcon\Mvc\Collection;

class Rights extends Collection
{
    public $id;
    
    public $name;
    
    public $code;
    
    public $type;
    
    public $parent;
    
    public $actions = [];
    
    public $langs;

    /**
     * 
     */
    public function update(){

    }

    public function dumpResult($collection,$document){

    }

    /**
     * @param $search
     * @param array $fields
     * @return array
     */
    public function search($search,$fields = []){
        $rights = Rights::find(array("conditions" => $search,"fields"=>$fields));
        return $rights;
    }

    /**
     * @param bool $id
     * @return array|Rights
     */
    public function create($id = false){
        if($id){
            $found = Rights::findFirst(array("conditions" => array(
                'id' => $id
            )));
            return $found;
        }
        $right = new Rights();
        $seq = ContentSettings\Seqs::createSeq('rights');
        $right->id = $seq->current;
        $right->save();
        return $right;
    }
}