<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Response;
use Cake\I18n\DateTime;

class GroupsController extends AppController
{
    public function index(): void
    {
        $this->viewBuilder()->setLayout('dashboard');
        $this->setCake2('groups', $this->fetchTable('Groups')->find('active')->all());
        $this->set('viewTitle', 'Groups');
        $this->set('addButtonTitle', '+ Group');
        $this->set('addButtonLink', '#');
    }

    public function view(?string $id = null): void
    {
        $this->viewBuilder()->setLayout('dashboard');
        $group = $this->fetchTable('Groups')->get($id, contain: ['Users']);
        $this->setCake2('group', $group);
        $this->set('viewTitle', $group->name);
        $this->set('addButtonTitle', '<i class="glyphicon glyphicon-trash"></i>');
        $this->set('addButtonModal', '#archive-modal');
    }

    public function add(): Response
    {
        $data = $this->request->getData('Group') ?? $this->request->getData();
        $table = $this->fetchTable('Groups');
        if ($table->save($table->newEntity($data))) {
            $this->Flash->success(__('The new group was created'));
        } else {
            $this->Flash->error(__('An error occurred while trying to create the group. Please try again'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function edit(?string $id = null): Response
    {
        $table = $this->fetchTable('Groups');
        $group = $table->get($id);
        $group->name = $this->request->getData('value') ?? $group->name;
        $ok = (bool)$table->save($group);
        if ($this->request->is('ajax')) {
            return $this->jsonResponse(['success' => $ok]);
        }
        return $this->redirect(['action' => 'index']);
    }

    public function delete(?string $id = null): Response
    {
        $this->fetchTable('Groups')->archive((int)$id);
        $this->Flash->success(__('The group has been deleted'));
        return $this->redirect(['action' => 'index']);
    }

    public function doesExist(): Response
    {
        $name = $this->request->getData('groupname');
        $exists = (bool)$this->fetchTable('Groups')->find()->where(['name' => $name])->first();
        return $this->jsonResponse(['valid' => !$exists]);
    }
}
