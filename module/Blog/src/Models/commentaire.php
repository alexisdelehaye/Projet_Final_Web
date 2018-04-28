<?php

namespace Blog\Models;

use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class commentaire implements InputFilterAwareInterface
{
    public $id_commentaire;
    public $user_id;
    public $text;
    public $date;
    public $post_id;
    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id_commentaire     = !empty($data['id_commentaire']) ? $data['id_commentaire'] : null;
        $this->user_id = !empty($data['user_id']) ? $data['user_id'] : null;
        $this->text = !empty($data['text']) ? $data['text'] : null;
        $this->post_id = !empty($data['post_id']) ? $data['post_id'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'id'     => $this->id_commentaire,
            'auteur' => $this->user_id,
            'titre' =>$this->text,
            'resume' => $this->date,
            'texte'  => $this->post_id,
        ];
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'id_commentaire',
            'required' => false,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'user_id',
            'required' => false,
            'filters' => [
                ['name' => ToInt::class]
                ],
                ]);

        $inputFilter->add([
            'name' => 'text',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,

                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'date',
            'required' => false,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 500,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'post_id',
            'required' => false,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);


        $this->inputFilter = $inputFilter;

        return $this->inputFilter;
    }
}