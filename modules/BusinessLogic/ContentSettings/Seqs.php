<?php

namespace Modules\BusinessLogic\ContentSettings;

use Modules\BusinessLogic\Models as Models;

class Seqs extends Base
{
    public $current;

    public $name;

    /**
     * @param $name
     * @return Seqs
     */
    public static function createSeq($name){
        $sModel = new Models\Seqs();
        $seq = $sModel->create($name);

        if(!$seq){
            $seq = new Models\Seqs();
            $seq->name = $name;
            $seq->current = 1;
            $seq->save();
        }else{
            $seq->current += 1;
            $seq->save();
        }
        $call = new Seqs();
        return $call->generate($seq);
    }

    /**
     * @param $obj
     * @return Seqs
     */
    public function generate($obj)
    {
        $seq = new Seqs();
        $seq->name = $obj->name;
        $seq->current = $obj->current;
        return $seq;
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $this->deleteCache($this);
        $model = new Models\Seqs();
        $seq = $model->create($this->name);
        if ($seq->delete()) {
            unset($this);
            return true;
        } else {
            return false;
        }
    }

}