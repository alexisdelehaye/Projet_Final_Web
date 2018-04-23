<?php

namespace Blog\Form;

use Zend\Form\Form;

class BlogForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('poste');

        //$this->setAttribute('method', 'GET'); // Default is POST

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'titre',
            'type' => 'Textarea',
            'options' => [
                'label' => 'titre',
            ],
        ]);

        $this->add([
            'name' => 'auteur',
            'type' => 'text',
            'options' => [
                'label' => 'auteur',
            ],

        ]);

        $this->add([
            'name'=>'resume',
            'type'=> 'Textarea',
            'options' => [
                'label' => 'resume',
            ],
        ]);

        $this->add([
            'name'=>'texte',
            'type'=> 'Textarea',
            'options' => [
                'label' => 'texte',
            ],
        ]);

        $this->add([
            'name' => 'date',
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