<?php
namespace User\Services;

use Zend\Db\Sql\Expression;
use Zend\Db\TableGateway\TableGatewayInterface;
use User\Models\users;
use Zend\Db\Sql\Select;
use Zend\View\Model\ViewModel;

class UserManager {
    protected $_tableGateway;

    public function __construct(TableGatewayInterface $tableGateway){
        $this->_tableGateway = $tableGateway;
    }

    public function findByUsername($username){
        return $this->_tableGateway->select(['username' => $username])->current();
    }

    public function findPrivilege($username){
        $user = $this->_tableGateway->select(['username' => $username])->current();
        return $user->idprivilege;
    }


    function randomSalt() {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789`~!@#$%^&*()-=_+';
        $str = '';
        for ($i = 0; $i < 5; ++$i) {
            $str .= $chars[rand(0,77)];
        }
	return $str;
}
    public function addUser($data){

        /*
        $select = new Select;
        $select->from('users')->columns(array('id' => new Expression('MAX(id)')));
        $sel = (int) $select;

        */
        $i=1;
        $test = $this->_tableGateway->select(['id' => $i])->current();
        while($test!=null){
            $i++;
            $test = $this->_tableGateway->select(['id' => $i])->current();
        }
        $user = new Users();
        $user->id = $i;
        $user->username=$data['username'];
        $user->email = $data['email'];
        $user->salt =  $this->randomSalt();
        $passwd = hash('sha256', $data['password'] . $user->salt);
        $user->password= $passwd;
        $user->idprivilege = 0;
        $array = (array) $user;
        $this->_tableGateway->insert($array);
        $view = new ViewModel();
        $view->setTemplate('blog/blog/index');
        return $view;
}
}
?>