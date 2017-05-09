<?php
/**
 * Created by PhpStorm.
 * User: Krisz
 * Date: 2016.12.06.
 * Time: 21:45
 */

namespace Modules\BusinessLogic\Search;

use Modules\BusinessLogic\ContentSettings\Document;
use Modules\BusinessLogic\Models;
use Phalcon\DI;

class BaseSearch
{
    /**
     * a redis objektumot
     * @var $redis
     */
    protected $redis;

    /**
     * Ebbe kerül bele az aktuális adatbázis model
     * @var $model
     */
    protected $model;

    /**
     * Ebbe kerül bele a az aktuális contentSetting objektum
     * @var $object
     */
    protected $object;

    /**
     * multiple id query
     * @var $ids
     */
    protected $ids;


    /**
     * aktuális nyelv
     * @var $lang
     */
    public $lang;

    /**
     * cache kulcs
     * @var string
     */

    private $onCache = true;

    /** @var string */
    private $loginCache = null;

    private $cacheType = "list";

    /**
     * ezzel lehet megadni ,hogy a generált képek mekkorák legyenek
     * @var bool  */
    public $imageSize = false;

    /**
     * a basic keresési feltételeket ő állítja össze
     * @return array
     */

    public $unwind = false;

    protected function _readSearch(){
        $params = array();
        
        if(isset($this->id) && !empty($this->id)){
            $params['id'] = $this->id; 
        }
        if(isset($this->ids) && !empty($this->ids)){
            //db.test.find({_id: {$in: ids}});
            $params['id'] = array('$in' => $this->ids);
        }
        if(isset($this->code) && !empty($this->ids)){
            $params['code'] = $this->code;
        }
        if(isset($this->name) && !empty($this->ids)){
            $params['name'] = $this->name;
        }
        
        return $params;
    }

    /**
     * a keresési feltételeknek megfelelően kikér egy objektumot az adatbázisból
     * @return bool
     */

    public function findFirst(){
        $params = array('conditions'=>$this->_readSearch());
        $result = $this->model->findFirst($params);
        if($result){
            $generated = $this->object->generate($result,false);

                /** @var Document $picture */
                foreach($generated->getPictures() as $picture){
                    if($this->imageSize){
                        $generated->pictures[] = $picture->getUrl($this->imageSize);
                    }else{
                        $generated->pictures[] = $picture->getUrl();
                    }
                }

            return $generated;
        }else{
            return false;
        }

    }

    /**
     * a keresési feltételeknek megfelelően kikér egy tömböt az adatbázisból
     * @return array|mixed
     */

    public function find(){

        /**@var \Predis\Client $cache*/
        $cache = $this->createRedis();

        $objectName = explode('\\',get_class($this->object));

        if($this->loginCache){
            $this->cacheType = $this->loginCache."_".$this->cacheType;
        }

        $cacheKey = $this->cacheType.'_'.$this->lang.'_'.end($objectName);

        if($cache->exists($cacheKey) && $this->onCache)
        {
            return json_decode($cache->get($cacheKey));
        }

        // ha ide jut itt mindig lesz egy cache mentés

        $params = array('conditions'=>$this->_readSearch());
        
        $results = $this->model->find($params);

        $items = [];
        foreach ($results as $result){
            $generated = $this->object->generate($result,$this->lang);

            /** @var Document $picture */
            foreach($generated->getPictures() as $picture){
                if($this->imageSize){
                    $generated->pictures[] = $picture->getUrl($this->imageSize);
                }else{
                    $generated->pictures[] = $picture->getUrl();
                }
            }
            
            $items[] = $generated;
            
        }
        
        if($this->onCache){
            $cache->set($cacheKey,json_encode($items));
            $cache->expire($cacheKey,3600*24*7);
        }

        return $items;
    }

    /**
     * Ha van id kikér egy objektumot az adatbázisból, ha nincs akkor létrehoz egyet
     * @param bool $id
     * @return bool
     */

    public function create($id = false){

        if(!$id){
            /**@var \Predis\Client $cache*/
            $cache = $this->createRedis();

            $objectName = explode('\\',get_class($this->object));

            $cacheKey = $this->cacheType.'_'.end($objectName);

            $cache->del([$cacheKey]);
        }



        $result = !$id?$this->model->create():$this->model->create($id);
        return $result?$this->object->generate($result,$this->lang):false;
    }

    /**
     * ez rakja össze a keresési feltételeket a mongonak
     * */
    protected function _readAggregation(){
        $params = array();

        return $params;
    }

    /** spéci kereséseket lehet csinálni vele */
    public function aggregate(){
        $aggregate = $this->model->aggregate($this->_readAggregation());
        return $aggregate['result'];
    }

    /**
     * Ezzel lehet módosítani a lekért adatok cache kulcsát
     * @param $name
     */
    public function setCacheType($name){
        $this->cacheType = $name;
    }

    /**
     * ezzel kapcsoljuk ki a cachet
     */

    public function disableCache(){
        $this->onCache = false;
    }

    /** felhasználónevet is belerakja a cache kulcsba */
    public function cacheByLogin($username){
        $this->loginCache = $username;
    }

    /**
     * ezzel érjük el a redist
     * @return mixed
     */

    private function createRedis(){

        $di = DI::getDefault();
        return $di['redis'];

    }
}