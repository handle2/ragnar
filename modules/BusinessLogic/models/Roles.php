<?php
namespace Modules\BusinessLogic\Models;

use Phalcon\Mvc\Collection;
use Modules\BusinessLogic\ContentSettings;

class Roles extends Collection
{
    public $id;
    
    public $name;
    
    public $code;
    
    public $type;
    
    public $rights;
    
    public $roles;
    
    public $langs;

    /**
     * 
     */
    public function update(){

    }

    public static function dumpResult($collection,$document){

    }

    /**
     * @param bool $id
     * @return array|Roles
     */
    public function create($id = false){
        if($id){
            $found = Roles::findFirst(array("conditions" => array(
                'id' => (int)$id
            )));
            return $found;
        }
        $roles = new Roles();
        $seq = ContentSettings\Seqs::createSeq('roles');
        $roles->id = $seq->current;
        $roles->save();
        return $roles;
    }

    /**
     * @param $search
     * @return array
     */
    public function search($search){
        $profile = Roles::find(array("conditions" => $search));
        return $profile;
    }
}