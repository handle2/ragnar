<?php

namespace Modules\BusinessLogic\Models;

use Phalcon\Mvc\Collection;

class Seqs extends Collection
{

    /**
     * 
     */
    public function update(){

    }

    public static function dumpResult($collection,$document){

    }

    /**
     * @param bool $name
     * @return $this|array
     */
    public function create($name = false){
        if($name){
            return $this->findFirst(['conditions'=>[
                'name'=> $name
            ]]);
        }
        return $this;
    }
}