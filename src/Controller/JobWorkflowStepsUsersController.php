<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Response;

class JobWorkflowStepsUsersController extends AppController
{
    public function add(): Response
    {
        $data = $this->request->getData('JobWorkflowStepsUser') ?? $this->request->getData();
        $table = $this->fetchTable('JobWorkflowStepsUsers');
        $ok = (bool)$table->save($table->newEntity($data));

        return $this->jsonResponse(['success' => $ok]);
    }

    public function delete(): Response
    {
        $data = $this->request->getData('JobWorkflowStepsUser') ?? $this->request->getData();
        $table = $this->fetchTable('JobWorkflowStepsUsers');
        $row = $table->find()->where([
            'user_id' => $data['user_id'] ?? 0,
            'job_workflow_step_id' => $data['job_workflow_step_id'] ?? 0,
        ])->first();
        if ($row) {
            $table->delete($row);
        }

        return $this->jsonResponse(['success' => true]);
    }
}
