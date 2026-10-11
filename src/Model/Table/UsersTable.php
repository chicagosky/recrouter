<?php
declare(strict_types=1);

namespace App\Model\Table;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class UsersTable extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('users');
        $this->setDisplayField('username');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Groups', ['foreignKey' => 'group_id']);
        $this->hasMany('FavoriteCandidates', ['foreignKey' => 'user_id']);
        $this->hasMany('FavoriteJobs', ['foreignKey' => 'user_id']);
        $this->hasMany('Jobs', ['foreignKey' => 'user_id']);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')->allowEmptyString('id', null, 'create')
            ->email('username')->notEmptyString('username')
            ->scalar('password')->allowEmptyString('password', null, 'update');

        return $validator;
    }

    public function beforeSave(\Cake\Event\EventInterface $event, \Cake\Datasource\EntityInterface $entity, \ArrayObject $options): void
    {
        parent::beforeSave($event, $entity, $options);
        if ($event->isStopped()) {
            return;
        }
        if ($entity->isDirty('password') && is_string($entity->get('password')) && $entity->get('password') !== '') {
            $hasher = new DefaultPasswordHasher();
            $entity->set('password', $hasher->hash($entity->get('password')));
        } elseif ($entity->isDirty('password') && ($entity->get('password') === '' || $entity->get('password') === null)) {
            $entity->unset('password');
        }
    }
}
