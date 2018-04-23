<?php

namespace Blog;
use Zend\Router\Http\Segment;
use Zend\Router\Http\Literal;
use Zend\ServiceManager\Factory\InvokableFactory;

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
];

