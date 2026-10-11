<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class CandidatesContractModelsTable extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('candidates_contract_models');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->belongsTo('Candidates', [
            'foreignKey' => 'candidate_id',
        ]);
        $this->belongsTo('ContractModels', [
            'foreignKey' => 'contract_model_id',
        ]);

    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->integer('id')->allowEmptyString('id', null, 'create');
        return $validator;
    }
}
