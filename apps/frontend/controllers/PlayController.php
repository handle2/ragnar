<?php
/**
 * Created by PhpStorm.
 * User: Krisz
 * Date: 2017.05.01.
 * Time: 23:15
 */

namespace Multiple\Frontend\Controllers;


use Modules\BusinessLogic\ContentSettings\Queue;
use Modules\BusinessLogic\Search\QueueSearch;

class PlayController extends ControllerBase
{
    public function stopQueueAction(){
        $search = QueueSearch::createQueueSearch();
        /** @var Queue $queue */
        $search->gamerID = $this->authUser->id;
        $queue = $search->findFirst();
        $queue->waiting = 0;
        $queue->save();
    }

    public function getQueueAction(){
        $search = QueueSearch::createQueueSearch();
        $search->notMe = $this->authUser->id;
        $search->waiting = 1;
        /** @var Queue $queue */
        $queue = $search->findFirst();
        if($queue){
            $queue->waiting = 0;
            $queue->save();
            return $this->api('200',"OK");
        }
        return $this->api('200',"NOPE");
    }

    public function setQueueAction(){
        $search = QueueSearch::createQueueSearch();
        /** @var Queue $queue */
        $queue = $search->create();
        $queue->gamerID = $this->authUser->id;
        $queue->waiting = 1;
        $queue->save();
    }
}