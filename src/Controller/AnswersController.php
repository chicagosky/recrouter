<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Response;

class AnswersController extends AppController
{
    protected array $publicActions = [];
    public array $paginate = ['limit' => 20];

    public function index(): void
    {
        $this->setCake2('answers', $this->paginate($this->fetchTable('Answers')));
    }

    public function view(?string $id = null): void
    {
        $this->setCake2('answer', $this->fetchTable('Answers')->get($id));
    }

    public function add(): ?Response
    {
        $table = $this->fetchTable('Answers');
        if ($this->request->is('post')) {
            $data = $this->request->getData('Answer') ?? $this->request->getData();
            if ($table->save($table->newEntity($data))) {
                $this->Flash->success(__('The record has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The record could not be saved. Please, try again.'));
        }
        return null;
    }

    public function edit(?string $id = null): ?Response
    {
        $table = $this->fetchTable('Answers');
        $entity = $table->get($id);
        if ($this->request->is(['post', 'put'])) {
            $data = $this->request->getData('Answer') ?? $this->request->getData();
            if ($table->save($table->patchEntity($entity, $data))) {
                $this->Flash->success(__('The record has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The record could not be saved. Please, try again.'));
        } else {
            $this->request = $this->request->withParsedBody(\App\Utility\Cake2Compat::toArray($entity));
        }
        return null;
    }

    public function delete(?string $id = null): Response
    {
        $table = $this->fetchTable('Answers');
        $entity = $table->get($id);
        if ($table->hasField('archived')) {
            $table->archive((int)$id);
        } else {
            $table->delete($entity);
        }
        $this->Flash->success(__('The record has been deleted.'));
        return $this->redirect(['action' => 'index']);
    }
}
