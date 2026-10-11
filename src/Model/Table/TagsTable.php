<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class TagsTable extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('tags');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->belongsToMany('Jobs', [
            'joinTable' => 'jobs_tags',
            'foreignKey' => 'tag_id',
            'targetForeignKey' => 'job_id',
        ]);
        $this->belongsToMany('Candidates', [
            'joinTable' => 'candidates_tags',
            'foreignKey' => 'tag_id',
            'targetForeignKey' => 'candidate_id',
        ]);

    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->integer('id')->allowEmptyString('id', null, 'create');
        return $validator;
    }
}
