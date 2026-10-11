<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class WorkflowsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    public function testIndex(): void
    {
        $locator = TableRegistry::getTableLocator();
        $workflow = $locator->get('Workflows')->saveOrFail($locator->get('Workflows')->newEntity([
            'name' => 'Default',
            'description' => 'Hiring pipeline',
            'archived' => '9999-12-31 00:00:00',
        ]));
        $locator->get('WorkflowSteps')->saveOrFail($locator->get('WorkflowSteps')->newEntity([
            'workflow_id' => $workflow->id,
            'workflow_step_type_id' => 1,
            'name' => 'Linked',
            'sequence' => 0,
            'archived' => '9999-12-31 00:00:00',
        ]));

        $this->get('/workflows');
        $this->assertResponseOk();
        $this->assertResponseContains('Default');
    }
}
