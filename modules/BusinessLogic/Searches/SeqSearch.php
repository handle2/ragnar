<?php
/**
 * Created by PhpStorm.
 * User: Krisz
 * Date: 2016.12.13.
 * Time: 15:00
 */

namespace Modules\BusinessLogic\Search;

use Modules\BusinessLogic\Models;
use Modules\BusinessLogic\ContentSettings;

class SeqSearch extends BaseSearch
{
    public $id;
    
    public $name;
    
    public $current;

    /**
     * @return SeqSearch
     */
    public static function createSeqSearch(){

        $search = new SeqSearch();
        $search->model = new Models\Seqs();
        /**@var \Modules\BusinessLogic\ContentSettings\Seqs $object*/
        $search->object = new ContentSettings\Seqs();
        return $search;
    }

    /**
     * @return array
     */
    public function _readSearch(){
        $params = parent::_readSearch();

        if($this->current){
            $params['current'] = $this->current;
        }

        return $params;
    }
}