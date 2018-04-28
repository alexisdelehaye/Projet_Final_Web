<?php

namespace Blog;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Authentication\AuthenticationService;
use Blog\Controller\BlogController;
use User\Services\UserManager;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [

                Models\posteTable::class => function($container) {
                    $tableGateway = $container->get(Models\posteTableGateway::class);
                    return new Models\posteTable($tableGateway);
                },
                Models\posteTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Models\poste());
                    return new TableGateway('poste', $dbAdapter, null, $resultSetPrototype);

                },
                Models\commentaireTable::class => function($container) {
                    $tableGateway = $container->get(Models\commentaireTableGateway::class);
                    return new Models\commentaireTable($tableGateway);
                },
                Models\commentaireTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Models\commentaire());
                    return new TableGateway('commentaire', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\BlogController::class => function($container) {
                    return new Controller\BlogController(
                        $container->get(Models\posteTable::class),
                        $container->get(AuthenticationService::class),
                        $container->get(UserManager::class),
                        $container->get(Models\commentaireTable::class)
                    );
                },
            ],
        ];
    }


}