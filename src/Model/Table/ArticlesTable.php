<?php
// src/Model/Table/ArticlesTable.php
namespace App\Model\Table;
// the Validator class
use Cake\Validation\Validator;

use Cake\ORM\Table;
use Cake\Utility\Text;

class ArticlesTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }



// Add the following method.

public function beforeSave($event, $entity, $options)
{
    if ($entity->isNew() && !$entity->slug) {
        $sluggedTitle = Text::slug($entity->title);
        // trim slug to maximum length defined in schema
        $entity->slug = substr($sluggedTitle, 0, 191);
    }
}
//validate your data when the save() method is called. 
public function validationDefault(Validator $validator)
{
    $validator
        ->allowEmptyString('title', false)
        ->minLength('title', 10)
        ->maxLength('title', 255)

        ->allowEmptyString('body', false)
        ->minLength('body', 10);

    return $validator;
}

}

