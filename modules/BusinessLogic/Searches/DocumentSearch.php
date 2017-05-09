<?php
/**
 * Created by PhpStorm.
 * User: Krisz
 * Date: 2017.01.08.
 * Time: 19:30
 */

namespace Modules\BusinessLogic\Search;


use Modules\BusinessLogic\ContentSettings\Document;
use Modules\BusinessLogic\Models\Documents;

class DocumentSearch extends BaseSearch
{
    
    public $name;
    
    public $type;
    
    public $size;
    
    public $ids;

    /**
     * @return DocumentSearch
     */
    public static function createDocumentSearch(){

        $search = new DocumentSearch();
        $search->model = new Documents();
        /**@var Document $object*/
        $search->object = new Document();
        return $search;
    }

    /**
     * @return array
     */
    public function _readSearch(){

        $params = parent::_readSearch();

        return $params;
    }

}