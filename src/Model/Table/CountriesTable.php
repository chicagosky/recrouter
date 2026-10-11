<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class CountriesTable extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('countries');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->hasMany('States', [
            'foreignKey' => 'country_id',
        ]);

    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->integer('id')->allowEmptyString('id', null, 'create');
        return $validator;
    }
}
