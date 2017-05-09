<?php

namespace Modules\BusinessLogic\ContentSettings;


use Modules\BusinessLogic\Models\Documents;
use Modules\BusinessLogic\Search\DocumentSearch;
use Phalcon\DI;
use Phalcon\Mvc\Collection\Document;

class Base
{
    /**
     * Képek visszaadása id alapján
     * @return array|mixed|null
     */
    public function getPictures(){
        if(!isset($this->pictureIds)){
            return [];
        }
        $search = DocumentSearch::createDocumentSearch();
        $search->ids = $this->pictureIds;
        $search->disableCache();
        $pictures = $search->find();
        return $pictures;
    }

    /**
     * Url kompatibilissé teszi a szöveget
     * @param $name
     * @param $picture
     * @return mixed
     */
    protected function urlMakeup($name,$picture = false){
        $ekezet = array('ö', 'ü', 'ó', 'ő', 'ú', 'ű', 'á', 'é', 'í');
        $normal = array('o', 'u', 'o', 'o', 'u', 'u', 'a', 'e', 'i');
        $text = $url = str_replace($ekezet, $normal, mb_strtolower($name));
        if($picture){
            return preg_replace('/[^a-z0-9.]/i', '_', $text);
        }
        return preg_replace('/[^a-z0-9]/i', '_', $text);
    }

    /**
     * Redis objektum lekérése
     * @return mixed
     */
    protected function createRedis(){
        $di = DI::getDefault();
        return $di['redis'];
    }

    /**
     * resolution képekhez objektum lekérése
     * @return mixed
     */
    protected function getResolutions(){
        $di = DI::getDefault();
        return $di['resolutions'];
    }

    /**
     * Objektumhoz tartozó cachek törlése
     * @param $obj
     * @param array $extraFields
     */
    protected function deleteCache($obj,$extraFields = []){
        $cache = $this->createRedis();

        $objectName = explode('\\',get_class($obj));

        /**@var \Predis\Client $cache*/
        $keys = $cache->keys('*');

        foreach ($keys as $key){
            if(strpos($key, end($objectName)) !== false || $this->strposa($key,$extraFields)){
                $cache->del([$key]);
            }
        }
    }

    private function strposa($haystack, $needle, $offset=0) {
        if(!is_array($needle)) $needle = array($needle);
        foreach($needle as $query) {
            if(strpos($haystack, $query, $offset) !== false) return true;
        }
        return false;
    }

    protected function deleteDocuments(){
        if(!isset($this->pictureIds)){
            return false;
        }
        $document = new Documents();
        foreach ($this->pictureIds as $id){
            $doc = $document->create($id);
            $doc->delete();
        }
    }
}