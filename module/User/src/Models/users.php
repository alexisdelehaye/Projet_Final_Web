<?php
namespace User\Models;

class users {
    public $id;
    public $username;
    public $email;
    public $salt;
    public $password;
    public $idprivilege;

    public function __construct(){

    }

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->username = (!empty($data['username'])) ? $data['username'] : null;
        $this->salt = (!empty($data['salt'])) ? $data['salt'] : null;
        $this->password = (!empty($data['password'])) ? $data['password'] : null;
        $this->idprivilege = (!empty($data['idprivilege'])) ? $data['idprivilege'] : null;
    }

    public function toValues(){
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'salt' => $this->salt,
            'password' => $this->password,
            'idPrivilege'=>$this->idprivilege,
        ];
    }
}
?>