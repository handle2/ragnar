<?php

namespace Modules\BusinessLogic\Search;


use Modules\BusinessLogic\ContentSettings\Queue;
use Modules\BusinessLogic\Models\Queues;

class QueueSearch extends BaseSearch
{
    public $id;

    public $gamerID;

    public $waiting;

    public $rank;

    public $notMe;

    /**
     * @return QueueSearch
     */
    public static function createQueueSearch(){
        $search = new QueueSearch();
        $search->model = new Queues();
        $search->object = new Queue();
        return $search;
    }

    /**
     * @return array
     */
    public function _readSearch(){
        $params = parent::_readSearch();

        if($this->id){
            $params['id'] = $this->id;
        }
        if($this->gamerID){
            $params['gamerID'] = $this->gamerID;
        }
        if($this->waiting){
            $params['waiting'] = $this->waiting;
        }
        if($this->rank){
            $params['rank'] = $this->rank;
        }
        if($this->notMe){
            $params['gamerID'] = array('$ne' => $this->notMe);
        }

        return $params;
    }
}