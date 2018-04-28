<?php

namespace Blog\Form;

use Zend\Form\Form;

class CommentairesForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('poste');

        //$this->setAttribute('method', 'GET'); // Default is POST

        $this->add([
            'name' => 'id_commentaire',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'user_id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'text',
            'type' => 'Textarea',
            'options' => [
                'label' => 'contenu',
            ],
        ]);


        $this->add([
            'name' => 'date',
            'type' => 'hidden',
        ]);


        $this->add([
            'name' => 'post_id',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}