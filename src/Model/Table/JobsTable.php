<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Database\Expression\QueryExpression;
use Cake\Validation\Validator;

class JobsTable extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('jobs');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Departments', ['foreignKey' => 'department_id']);
        $this->belongsTo('Priorities', ['foreignKey' => 'priority_id']);
        $this->belongsTo('Workflows', ['foreignKey' => 'workflow_id']);
        $this->belongsTo('Users', ['foreignKey' => 'user_id']);
        $this->hasOne('Surveys', ['foreignKey' => 'job_id']);
        $this->hasMany('JobExtIds', ['foreignKey' => 'job_id']);
        $this->hasMany('JobWorkflowSteps', ['foreignKey' => 'job_id']);
        $this->hasMany('CandidatesJobs', ['foreignKey' => 'job_id']);
        $this->belongsToMany('ContractModels', [
            'joinTable' => 'contract_models_jobs',
            'foreignKey' => 'job_id',
            'targetForeignKey' => 'contract_model_id',
        ]);
        $this->belongsToMany('Tags', [
            'joinTable' => 'jobs_tags',
            'foreignKey' => 'job_id',
            'targetForeignKey' => 'tag_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->integer('id')->allowEmptyString('id', null, 'create')
            ->scalar('title')->notEmptyString('title');

        return $validator;
    }

    /**
     * Replacement for SEARCH_JOBS. Returns rows with a job_id key.
     *
     * @param array $filters
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
                    $like = str_replace(['%', '_'], ['\%', '\_'], trim($token, '%'));
                    $like = '%' . $like . '%';
                    $or->like('title', $like)
                        ->like('description', $like)
                        ->like('skills', $like)
                        ->like('city', $like);
                }

                return $or;
            });
        }
        $ids = [];
        foreach ($query->all() as $job) {
            $ids[] = ['job_id' => (int)$job->id];
        }

        return $ids;
    }
}
