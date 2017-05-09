<?php
/**
 * Created by PhpStorm.
 * User: Krisz
 * Date: 2017.01.15.
 * Time: 16:39
 */

namespace Modules\BusinessLogic\ContentSettings;


use Modules\BusinessLogic\Models\Languages;

class Language extends Base
{
    public $id;
    
    public $name;
    
    public $code;
    
    public $langs;

    /**
     * @param Languages $obj
     * @param $lang
     * @return Language
     */
    public function generate(Languages $obj,$lang){
        $self = new Language();
        $self->id = $obj->id;
        $langs = (object)$obj->langs;
        $self->name = isset($langs->{$lang}['name'])?$langs->{$lang}['name']:$obj->name;
        $self->code = isset($langs->{$lang}['code'])?$langs->{$lang}['code']:$obj->code;
        $self->langs = $obj->langs;
        return $self;
    }

    /**
     * @return bool
     */
    public function delete(){

        $this->deleteCache($this);

        $model = new Languages();
        $self = $model->create($this->id);
        if($self->delete()){
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

        $model = new Languages();
        $self = $model->create($this->id);
        $self->id = $this->id;
        $self->name = $this->name;
        $self->code = $this->code;
        if($self->save()){
            return true;
        }else{
            return false;
        }
    }
}