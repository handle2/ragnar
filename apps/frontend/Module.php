<?php

namespace Multiple\Frontend;

use Phalcon\Cache\Backend\Mongo;
use Phalcon\Http\Response\Cookies;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\DiInterface;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\Model\Manager as CollectionManager;
use Phalcon\Session\Adapter\Files as Session;

class Module implements ModuleDefinitionInterface
{
    /**
     * Registers the module auto-loader
     *
     * @param DiInterface $di
     */
    public function registerAutoloaders(DiInterface $di = null)
    {
        $loader = new Loader();

        $loader->registerNamespaces(
            [
                'Multiple\Frontend\Controllers' => 'apps/frontend/controllers/',
                'Multiple\Frontend\Models' => 'apps/frontend/models/',
                'Modules\BusinessLogic\Models' => 'modules/BusinessLogic/models/',
                'Modules\BusinessLogic\ContentSettings' => 'modules/BusinessLogic/ContentSettings/',
                'Modules\BusinessLogic\Search' => 'modules/BusinessLogic/Searches/',
            ]
        );

        $loader->register();
    }

    /**
     * Registers services related to the module
     *
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {

        /**
         * Read configuration
         */
        $config = include __DIR__ . "/../../config/config.php";
        $di->set('config', $config);


        // Registering a dispatcher
        $di->set('dispatcher', function () {
            $dispatcher = new Dispatcher();

            $eventManager = new Manager();

            // Attach a event listener to the dispatcher (if any)
            // For example:
            // $eventManager->attach('dispatch', new \My\Awesome\Acl('frontend'));

            $dispatcher->setEventsManager($eventManager);
            $dispatcher->setDefaultNamespace('Multiple\Frontend\Controllers\\');
            return $dispatcher;
        });

        // Registering the view component
        $di->set('view', function () {
            $view = new View();
            $view->setViewsDir('apps/frontend/views/');
            $view->setLayoutsDir('apps/frontend/views/');
            $view->setMainView('index');
            return $view;
        });

        $di->set('db', function () {
            return new Mysql(
                [
                    "host" => "localhost",
                    "username" => "root",
                    "password" => "secret",
                    "dbname" => "invo"
                ]
            );
        });

        $di->set('mongo', function() {
            $mongo = new \MongoClient();
            return $mongo->selectDB("ragnar");
        }, true);

        $di->set('collectionManager', function(){
            return new \Phalcon\Mvc\Collection\Manager();
        }, true);

        $di->set('cookies', function () {
            $cookies = new Cookies();
            $cookies->useEncryption(false);
            return $cookies;
        },true);

        $di->set('session', function () {
            $session = new Session();
            $session->start();
            return $session;
        },true);
    }
}
