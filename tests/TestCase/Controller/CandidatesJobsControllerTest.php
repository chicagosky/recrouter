<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Model\Entity\WorkflowStepType;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class CandidatesJobsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    protected function seedLink(): array
    {
        $locator = TableRegistry::getTableLocator();
        $workflow = $locator->get('Workflows')->saveOrFail($locator->get('Workflows')->newEntity([
            'name' => 'Default',
            'archived' => '9999-12-31 00:00:00',
        ]));
        $step = $locator->get('WorkflowSteps')->saveOrFail($locator->get('WorkflowSteps')->newEntity([
            'workflow_id' => $workflow->id,
            'workflow_step_type_id' => WorkflowStepType::STEP_TYPE_LINK,
            'name' => 'Linked',
            'sequence' => 0,
            'archived' => '9999-12-31 00:00:00',
        ]));
        $job = $locator->get('Jobs')->saveOrFail($locator->get('Jobs')->newEntity([
            'title' => 'Engineer',
            'workflow_id' => $workflow->id,
            'user_id' => 1,
            'archived' => '9999-12-31 00:00:00',
        ]));
        $locator->get('JobWorkflowSteps')->saveOrFail($locator->get('JobWorkflowSteps')->newEntity([
            'job_id' => $job->id,
            'workflow_id' => $workflow->id,
            'workflow_step_id' => $step->id,
            'sequence' => 0,
        ]));
        $candidate = $locator->get('Candidates')->saveOrFail($locator->get('Candidates')->newEntity([
            'firstname' => 'Jane',
            'lastname' => 'Doe',
            'email' => 'jane@example.com',
            'archived' => '9999-12-31 00:00:00',
        ]));

        return ['job' => $job, 'candidate' => $candidate];
    }

    public function testLinkCreatesRow(): void
    {
        $seed = $this->seedLink();
        $this->enableCsrfToken();
        $this->post('/CandidatesJobs/link/' . $seed['candidate']->id . '/' . $seed['job']->id);
        $this->assertResponseOk();
        $this->assertResponseContains('"success":true');
        $links = TableRegistry::getTableLocator()->get('CandidatesJobs');
        $this->assertSame(1, $links->find()->where([
            'candidate_id' => $seed['candidate']->id,
            'job_id' => $seed['job']->id,
        ])->count());
    }

    public function testViewRendersTimeline(): void
    {
        $seed = $this->seedLink();
        $locator = TableRegistry::getTableLocator();
        $link = $locator->get('CandidatesJobs')->saveOrFail($locator->get('CandidatesJobs')->newEntity([
            'candidate_id' => $seed['candidate']->id,
            'job_id' => $seed['job']->id,
            'archived' => '9999-12-31 00:00:00',
            'hired' => '9999-12-31 00:00:00',
            'rejected' => '9999-12-31 00:00:00',
        ]));

        $this->get('/CandidatesJobs/view/' . $link->id);
        $this->assertResponseOk();
        $this->assertResponseContains('Jane');
        $this->assertResponseContains('Engineer');
        $this->assertResponseNotContains('Missing Element');
    }

    public function testHireMarksCandidate(): void
    {
        $seed = $this->seedLink();
        $locator = TableRegistry::getTableLocator();
        $link = $locator->get('CandidatesJobs')->saveOrFail($locator->get('CandidatesJobs')->newEntity([
            'candidate_id' => $seed['candidate']->id,
            'job_id' => $seed['job']->id,
            'archived' => '9999-12-31 00:00:00',
            'hired' => '9999-12-31 00:00:00',
            'rejected' => '9999-12-31 00:00:00',
        ]));

        $this->enableCsrfToken();
        $this->get('/CandidatesJobs/hire/' . $link->id);
        $this->assertRedirectContains('view/' . $link->id);

        $fresh = $locator->get('CandidatesJobs')->get($link->id);
        $hired = $fresh->hired instanceof \Cake\I18n\DateTime
            ? $fresh->hired->format('Y-m-d H:i:s')
            : (string)$fresh->hired;
        $this->assertNotSame('9999-12-31 00:00:00', $hired);
    }
}
