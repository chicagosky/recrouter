<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Response;
use Cake\I18n\DateTime;

class WorkflowStepsController extends AppController
{
    public function index(?string $workflowId = null): Response
    {
        $steps = $this->fetchTable('WorkflowSteps')->find('active')
            ->contain(['WorkflowStepUsers' => ['Users']])
            ->where(['workflow_id' => $workflowId])
            ->orderBy(['sequence' => 'ASC'])
            ->all();
        return $this->jsonResponse(\App\Utility\Cake2Compat::toArray($steps));
    }

    public function add(): Response
    {
        $data = $this->request->getData('WorkflowStep') ?? $this->request->getData();
        $seq = (int)($data['sequence'] ?? 0);
        $this->fetchTable('WorkflowSteps')->updateAll(
            ['sequence' => $this->fetchTable('WorkflowSteps')->query()->newExpr('sequence + 1')],
            ['sequence >=' => $seq, 'workflow_id' => $data['workflow_id'] ?? 0]
        );
        $this->fetchTable('WorkflowSteps')->save($this->fetchTable('WorkflowSteps')->newEntity($data));
        return $this->redirect(['controller' => 'Workflows', 'action' => 'index']);
    }

    public function edit(?string $id = null): Response
    {
        $table = $this->fetchTable('WorkflowSteps');
        $step = $table->get($id);
        $value = $this->request->getData('value');
        if ($value === '' || $value === null) {
            return $this->redirect(['action' => 'delete', $id]);
        }
        $step->name = $value;
        $ok = (bool)$table->save($step);
        return $this->jsonResponse(['success' => $ok, 'message' => $ok ? 'The edit has been saved' : 'The edit could not be saved. Please try again']);
    }

    public function delete(?string $id = null): Response
    {
        $this->fetchTable('WorkflowSteps')->archive((int)$id);
        return $this->redirect(['controller' => 'Workflows', 'action' => 'index']);
    }

    public function reorder(?string $workflowId = null): Response
    {
        $order = $this->request->getData('order') ?? [];
        $table = $this->fetchTable('WorkflowSteps');
        foreach ((array)$order as $sequence => $id) {
            $step = $table->get($id);
            $step->sequence = (int)$sequence;
            $table->save($step);
        }
        return $this->jsonResponse(['success' => true]);
    }
}
