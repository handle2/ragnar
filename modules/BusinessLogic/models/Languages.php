<?php
/**
 * Created by PhpStorm.
 * User: Krisz
 * Date: 2017.01.15.
 * Time: 16:39
 */

namespace Modules\BusinessLogic\Models;


use Modules\BusinessLogic\ContentSettings\Seqs;
use Phalcon\Mvc\Collection;

class Languages extends Collection
{
    public $id;
    
    public $name;
    
    public $code;
    
    public $langs;

    /**
     * 
     */
    public function update(){

    }

    public static function dumpResult($collection,$document){

    }

    /**
     * @param $search
     * @return array
     */
    public function search($search){
        $found = Languages::find(array("conditions" => $search));
        return $found;
    }

    /**
     * @param bool $id
     * @return array|Languages
     */
    public function create($id = false){
        if($id){
            $found = Languages::findFirst(array("conditions" => array(
                'id' => $id
            )));
            return $found;
        }

        $seq = Seqs::createSeq('languages');
        $self = new Languages();
        $self->id = $seq->current;
        $self->save();
        return $self;
    }
}