<?php

namespace Blog\Models;

use RuntimeException;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGatewayInterface;

class commentaireTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getCommentaire($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id_commentaire' => $id]);
        $row = $rowset->current();
        /*
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }
*/
        return $row;
    }

    public function saveCommentaire(commentaire $commentaire,$user_id,$post_id,$idCom)
    {
        $data = [
            'id_commentaire' => $idCom,
            'user_id' => $user_id,
            'text'  => $commentaire->text,
            'date' => (string) date("d/m/Y"). " -".date("h:i:sa"),
            'post_id' => $post_id

        ];

        $this->tableGateway->insert($data);
        return;

        /*
        $id = $idCom;
        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getCommentaire($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id_commentaire' => $id]);

        */
    }

    public function deleteCommentaire($id)
    {
        $this->tableGateway->delete(['id_commentaire' => (int) $id]);
    }

    public function getListeCommentaireParPost($idPoste)
    {
        $id = $idPoste;
        /*
        $listeCom = $this->tableGateway->select((function (Select $select) {
            $select->columns(['post_id']);
            $select->where(['post_id' => $this->]);
        }));
        */
        $listeCom = $this->tableGateway->select(array('post_id'=> $idPoste));
        return $listeCom;
    }

    public function getLastidComm(){
        $i=1;
        $test = $this->getCommentaire($i);
        while($test !=null) {
            $i++;
            $test = $this->getCommentaire($i);
        }
        return $i;
    }

}
