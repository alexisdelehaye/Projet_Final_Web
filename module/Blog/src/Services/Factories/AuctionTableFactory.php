<?php
namespace Application\Services\Factories;

use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Zend\Authentication\Storage\Session as SessionStorage;
use Interop\Container\ContainerInterface;
use Application\Services\AuctionTableGateway;
use Application\Services\AuctionTable;


/**
 * The factory responsible for creating of authentication service.
 */
class AuctionTableFactory implements FactoryInterface
{
    /**
     * This method creates the Zend\Authentication\AuthenticationService service 
     * and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, 
                    $requestedName, array $options = null)
    {
        $tableGateway = $container->get(AuctionTableGateway::class);
        $table = new AuctionTable($tableGateway);
        return $table;
    }
}
