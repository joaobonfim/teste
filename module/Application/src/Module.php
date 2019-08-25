<?php
namespace Application;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;

class Module implements BootstrapListenerInterface
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(EventInterface $e)
    {
        /**
         * @var $serviceManager \Zend\ServiceManager\ServiceManager
         * @var $adapter Adapter
         */
        $serviceManager = $e->getApplication()->getServiceManager();

        $adapter = $serviceManager->get(Adapter::class);

        $statement = $adapter->createStatement('SELECT * FROM users');
        $statement->prepare();
        $result = $statement->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet;
            $resultSet->initialize($result);

            foreach ($resultSet as $row) {
                echo $row->id . PHP_EOL;
                echo $row->name . PHP_EOL;
                echo $row->email . PHP_EOL;
            }
        }

        die;
    }
}
