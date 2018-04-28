<?php

namespace Blog;
use Zend\Router\Http\Segment;
use Zend\Router\Http\Literal;
use Zend\ServiceManager\Factory\InvokableFactory;
use User\Controller\AuthController;

return [
    'router' => [
        'routes' => [
            'Blog' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/blog[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\BlogController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'Auth' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/Auth[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'SignUp',
                    ],
                ],
            ]
            ],
        ],

    'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',

        ],
    ],
    /*
    'controllers' => [
            'factories' => [
                Controller\BlogController::class => function($container) {
                    return new Controller\BlogController(
                        $container->get(Models\posteTable::class)
                    );
                },

            ],
        ],


*/
'service_manager' => [
    'factories' => [
        Services\AuctionTable::class => Services\Factories\AuctionTableFactory::class,
        Services\AuctionTableGateway::class => Services\Factories\AuctionTableGatewayFactory::class,
        Services\NavManager::class => Services\Factories\NavManagerFactory::class,
     ],
],



];


