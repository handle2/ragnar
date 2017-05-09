<?php
/**
 * Created by PhpStorm.
 * User: Krisz
 * Date: 2017.01.15.
 * Time: 16:39
 */

namespace Modules\BusinessLogic\Search;


use Modules\BusinessLogic\ContentSettings\Language;
use Modules\BusinessLogic\Models\Languages;

class LanguageSearch extends BaseSearch
{

    public $name;
    
    public $code;
    
    public $ids;

    /**
     * @return LanguageSearch
     */
    public static function createLanguageSearch(){
        $search = new LanguageSearch();
        $search->model = new Languages();
        /**@var Language $object*/
        $search->object = new Language();
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