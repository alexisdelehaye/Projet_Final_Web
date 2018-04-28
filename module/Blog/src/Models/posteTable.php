<?php

namespace Blog\Models;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class posteTable
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

    public function getPoste($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function savePoste(poste $poste, $auteur)
    {
        $data = [
            'auteur' => $auteur,
            'texte'  => $poste->texte,
            'titre' => $poste->titre,
            'resume' =>$poste->resume,
            'date' => (string)"le ". date("d/m/Y"). " à ".date("h:i:sa"),

        ];

        $id = (int) $poste->id;
        //$date= (string)"le ". date("d/m/Y"). " à ".date("h:i:sa");
        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getPoste($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
        //$this->tableGateway->update($data, ['date' => $date]);

    }

    public function deletePoste($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}