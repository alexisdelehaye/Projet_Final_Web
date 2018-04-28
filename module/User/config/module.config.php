<?php
namespace User;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'login' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            // Utilisation du constructeur AuthControllerFactory pour construire AuthController
            Controller\AuthController::class => Controller\Factories\AuthControllerFactory::class,
        ],
    ],
    
    'service_manager' => [
        'factories' => [
            // Factory permettant de gérer la base de données
            Services\UserManager::class => Services\Factories\UserManagerFactory::class,
            // Factory permettant de gérer la passerelle entre la base de données et UserManager
            Services\UserGateway::class => Services\Factories\UserGatewayFactory::class,
            // Factory permettant de gérer tous les principes d'authentification
            Services\AuthManager::class => Services\Factories\AuthManagerFactory::class,
            // Factory permettant de créer l'Adapteur implémentant l'interface d'authentification
            Services\AuthAdapter::class => Services\Factories\AuthAdapterFactory::class,
            // Service d'authentification de zend
            \Zend\Authentication\AuthenticationService::class => Services\Factories\AuthenticationServiceFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];