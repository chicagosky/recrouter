<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Response;
use Cake\I18n\DateTime;

class WorkflowsController extends AppController
{
    public function index(): void
    {
        $this->viewBuilder()->setLayout('dashboard');
        $this->set('viewTitle', 'Workflows');
        $this->set('addButtonTitle', '+ Workflow');
        $this->set('addButtonModal', '#');
        $workflows = $this->fetchTable('Workflows')->find('active')
            ->contain(['WorkflowSteps' => ['WorkflowStepTypes', 'WorkflowStepUsers' => ['Users']]])
            ->all();
        $this->setCake2('workflows', $workflows);
        $this->set('allStepTypes', $this->fetchTable('WorkflowStepTypes')->find('list')->toArray());
    }

    public function add(): Response
    {
        $data = $this->request->getData('Workflow') ?? $this->request->getData();
        $table = $this->fetchTable('Workflows');
        $entity = $table->newEntity($data);
        if ($table->save($entity)) {
            $steps = $this->fetchTable('WorkflowSteps');
            $steps->save($steps->newEntity(['workflow_id' => $entity->id, 'name' => 'Linked', 'workflow_step_type_id' => 1, 'sequence' => 0]));
            $steps->save($steps->newEntity(['workflow_id' => $entity->id, 'name' => 'Closed', 'workflow_step_type_id' => 2, 'sequence' => 1]));
            $this->Flash->success(__('The workflow has been saved'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function edit(?string $id = null): Response
    {
        $table = $this->fetchTable('Workflows');
        $wf = $table->get($id);
        $wf->name = $this->request->getData('value') ?? $wf->name;
        $table->save($wf);
        return $this->redirect(['action' => 'index']);
    }

    public function delete(?string $id = null): Response
    {
        $now = DateTime::now();
        $this->fetchTable('WorkflowStepUsers')->updateAll(['archived' => $now], ['workflow_id' => $id]);
        $this->fetchTable('WorkflowSteps')->updateAll(['archived' => $now], ['workflow_id' => $id]);
        $this->fetchTable('Workflows')->archive((int)$id);
        $this->Flash->success(__('The workflow has been archived'));
        return $this->redirect(['action' => 'index']);
    }

    public function doesExist(): Response
    {
        $name = $this->request->getData('workflowname');
        $exists = (bool)$this->fetchTable('Workflows')->find()->where(['name' => $name])->first();
        return $this->jsonResponse(['valid' => !$exists]);
    }
}
