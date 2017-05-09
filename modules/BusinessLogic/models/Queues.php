<?php
/**
 * Created by PhpStorm.
 * User: Krisz
 * Date: 2017.05.01.
 * Time: 22:21
 */

namespace Modules\BusinessLogic\Models;


use Modules\BusinessLogic\ContentSettings\Seqs;
use Phalcon\Mvc\Collection;

class Queues extends Collection
{
    public $id;

    public $gamerID;

    public $waiting;

    public $rank;


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
        $items = Queues::find(array("conditions" => $search,"fields"=>$fields));
        return $items;
    }

    /**
     * @param bool $id
     * @return array|Rights
     */
    public function create($id = false){
        if($id){
            $found = Queues::findFirst(array("conditions" => array(
                'id' => $id
            )));
            return $found;
        }
        $item = new Queues();
        $seq = Seqs::createSeq('queue');
        $item->id = $seq->current;
        $item->save();
        return $item;
    }
}