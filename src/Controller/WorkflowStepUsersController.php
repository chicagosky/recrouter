<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Response;

class WorkflowStepUsersController extends AppController
{
    public function add(?string $id = null): Response
    {
        $data = $this->request->getData('WorkflowStepUser') ?? $this->request->getData();
        if ($id && empty($data['workflow_step_id'])) {
            $data['workflow_step_id'] = $id;
        }
        $table = $this->fetchTable('WorkflowStepUsers');
        $ok = (bool)$table->save($table->newEntity($data));

        return $this->jsonResponse(['success' => $ok]);
    }

    public function delete(?string $id = null): Response
    {
        $table = $this->fetchTable('WorkflowStepUsers');
        $table->delete($table->get($id));

        return $this->jsonResponse(['success' => true]);
    }
}
