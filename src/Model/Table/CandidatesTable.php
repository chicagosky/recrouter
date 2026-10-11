<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Database\Expression\QueryExpression;
use Cake\Validation\Validator;

class CandidatesTable extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('candidates');
        $this->setDisplayField('email');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Users', ['foreignKey' => 'user_id']);
        $this->hasMany('CandidatesJobs', ['foreignKey' => 'candidate_id']);
        $this->belongsToMany('ContractModels', [
            'joinTable' => 'candidates_contract_models',
            'foreignKey' => 'candidate_id',
            'targetForeignKey' => 'contract_model_id',
        ]);
        $this->belongsToMany('Tags', [
            'joinTable' => 'candidates_tags',
            'foreignKey' => 'candidate_id',
            'targetForeignKey' => 'tag_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->integer('id')->allowEmptyString('id', null, 'create');

        return $validator;
    }

    /**
     * Replacement for the missing Candidate::filter / SEARCH_CANDIDATES procedure.
     *
     * @return array<int, array{job_id:int}>
     */
    public function filter(array $filters): array
    {
        $keywords = (string)($filters['keywords'] ?? '%');
        $tokens = array_filter(explode('|', $keywords));
        $query = $this->find()->select(['id']);
        if ($tokens && !(count($tokens) === 1 && $tokens[0] === '%')) {
            $query->where(function (QueryExpression $exp) use ($tokens) {
                $or = $exp->or([]);
                foreach ($tokens as $token) {
                    $like = '%' . trim($token, '%') . '%';
                    $or->like('firstname', $like)
                        ->like('lastname', $like)
                        ->like('email', $like)
                        ->like('notes', $like)
                        ->like('city', $like);
                }

                return $or;
            });
        }
        $ids = [];
        foreach ($query->all() as $row) {
            // Preserve the original (buggy) job_id key that CandidatesController mapped.
            $ids[] = ['job_id' => (int)$row->id];
        }

        return $ids;
    }
}
