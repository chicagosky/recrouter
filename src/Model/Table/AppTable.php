<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\I18n\DateTime;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;

class AppTable extends Table
{
    public const SENTINEL = '9999-12-31 00:00:00';

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    public function findActive(SelectQuery $query): SelectQuery
    {
        if ($this->hasField('archived')) {
            $query->where([$this->getAlias() . '.archived >' => DateTime::now()]);
        }

        return $query;
    }

    public function archive(int|string $id): bool
    {
        $entity = $this->get($id);
        $entity->set('archived', DateTime::now());

        return (bool)$this->save($entity);
    }

    public function beforeSave(\Cake\Event\EventInterface $event, \Cake\Datasource\EntityInterface $entity, \ArrayObject $options): void
    {
        if (
            $this->hasField('modified')
            && !$entity->isNew()
            && $entity->isDirty('modified')
            && $entity->getOriginal('modified')
        ) {
            $existing = $this->find()
                ->select(['id', 'modified'])
                ->where([
                    'id' => $entity->get('id'),
                    'modified' => $entity->getOriginal('modified'),
                ])
                ->first();
            if (!$existing) {
                $entity->setError('modified', ['Concurrency error: the record was changed by someone else.']);
                $event->setResult(false);
                $event->stopPropagation();

                return;
            }
        }

        if ($this->hasField('archived') && $entity->isNew() && $entity->get('archived') === null) {
            $entity->set('archived', self::SENTINEL);
        }
    }
}
