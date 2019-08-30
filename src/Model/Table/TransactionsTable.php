<?php
// src/Model/Table/TransactionsTable.php
namespace App\Model\Table;
// the Validator class
use Cake\Validation\Validator;

use Cake\ORM\Table;
use Cake\Utility\Text;

class TransactionsTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }



    // Add the following method.

    public function beforeSave($event, $entity, $options)
    {
        if ($entity->isNew() && !$entity->slug) {
        // $data=$entity->sourcemac . $entity->destmac;
            // $sluggedMac = Text::slug($data) ;
            // echo $sluggedMac;
            // trim slug to maximum length defined in schema
            
                // $entity->slug = substr($entity->sourcemac, 0, 191);
                // if($entity->slug == null){
                    $entity->slug = 'default slug';
                // }
        }
    }

    //validate your data when the save() method is called. 
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmptyString('title', false)
            ->allowEmptyString('sourcemac', false)
            ->allowEmptyString('destmac', false)
            ->allowEmptyString('sulg','Slug is' ,false)
            ->minLength('sourcemac', 20)
            ->minLength('destmac', 20);
        return $validator;
    }
}
