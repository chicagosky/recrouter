<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Response;
use Cake\I18n\DateTime;

class DepartmentsController extends AppController
{
    protected array $publicActions = [];

    public function index(): void
    {
        $this->viewBuilder()->setLayout('dashboard');
        $this->set('viewTitle', 'Departments');
        $threaded = $this->fetchTable('Departments')->find('threaded')->all();
        $this->set('departments', \App\Utility\Cake2Compat::toArray($threaded));
        $this->set('allDepartments', $this->fetchTable('Departments')->find('list')->toArray());
    }

    public function add(): Response
    {
        $data = $this->request->getData('Department') ?? $this->request->getData();
        if ((int)($data['parent_id'] ?? 0) < 1) {
            $data['parent_id'] = 1;
        }
        $table = $this->fetchTable('Departments');
        if ($table->save($table->newEntity($data))) {
            $this->Flash->success(__('The new department has been saved'));
        } else {
            $this->Flash->error(__('There was a problem in saving the department. Please try again'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function edit(?string $id = null): Response
    {
        $table = $this->fetchTable('Departments');
        $entity = $table->patchEntity($table->get($id), $this->request->getData('Department') ?? $this->request->getData());
        $table->save($entity);
        $this->Flash->success(__('The changes have been saved'));
        return $this->redirect(['action' => 'index']);
    }

    public function delete(?string $id = null): Response
    {
        $table = $this->fetchTable('Departments');
        $table->delete($table->get($id));
        $this->Flash->success(__('The department node hierarchy has been deleted'));
        return $this->redirect(['action' => 'index']);
    }
}
