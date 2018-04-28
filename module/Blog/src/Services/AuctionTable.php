<?php
namespace Application\Services;

use Zend\Db\TableGateway\TableGatewayInterface;  
use Application\Model\Auction;

class AuctionTable {
    protected $_tableGateway;

    public function __construct(TableGatewayInterface $tableGateway){
        $this->_tableGateway = $tableGateway;
    }

    public function fetchAll() { 
        $resultSet = $this->_tableGateway->select(); 
        $return = array();
        foreach( $resultSet as $r )
            $return[]=$r;
        return $return; 
    }

    public function insert(Auction $a){
        $this->_tableGateway->insert($a->toValues());
    }

    public function find($id){
        return $this->_tableGateway->select(['auc' => $id])->current();
    }

    public function update(Auction $toUpdate, $data){
        return $this->_tableGateway->update($data,['auc' => $toUpdate->_auc]);
    }
}
?>