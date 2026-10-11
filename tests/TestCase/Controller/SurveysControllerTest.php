<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class SurveysControllerTest extends TestCase
{
    use IntegrationTestTrait;

    public function testResponseSavesAnswers(): void
    {
        $locator = TableRegistry::getTableLocator();
        $job = $locator->get('Jobs')->saveOrFail($locator->get('Jobs')->newEntity([
            'title' => 'Survey Job',
            'archived' => '9999-12-31 00:00:00',
        ]));
        $survey = $locator->get('Surveys')->saveOrFail($locator->get('Surveys')->newEntity([
            'job_id' => $job->id,
            'name' => 'Screening',
            'archived' => '9999-12-31 00:00:00',
        ]));
        $question = $locator->get('Questions')->saveOrFail($locator->get('Questions')->newEntity([
            'survey_id' => $survey->id,
            'name' => 'Why?',
            'type' => '1',
        ]));
        $candidate = $locator->get('Candidates')->saveOrFail($locator->get('Candidates')->newEntity([
            'firstname' => 'Pat',
            'lastname' => 'Candidate',
            'email' => 'pat@example.com',
            'archived' => '9999-12-31 00:00:00',
        ]));
        $link = $locator->get('CandidatesJobs')->saveOrFail($locator->get('CandidatesJobs')->newEntity([
            'candidate_id' => $candidate->id,
            'job_id' => $job->id,
            'archived' => '9999-12-31 00:00:00',
        ]));

        $this->enableCsrfToken();
        $this->post('/surveys/response/' . $survey->id . '/' . $link->id, [
            'responses' => json_encode([
                ['question_id' => $question->id, 'candidates_job_id' => $link->id, 'answer_text' => 'Because'],
            ]),
        ]);
        $this->assertResponseOk();
        $answers = $locator->get('Answers');
        $this->assertSame(1, $answers->find()->where(['question_id' => $question->id])->count());
    }

    public function testResponsePageRenders(): void
    {
        $locator = TableRegistry::getTableLocator();
        $job = $locator->get('Jobs')->saveOrFail($locator->get('Jobs')->newEntity([
            'title' => 'Browser Survey Job',
            'city' => 'Austin',
            'state' => 'TX',
            'archived' => '9999-12-31 00:00:00',
        ]));
        $survey = $locator->get('Surveys')->saveOrFail($locator->get('Surveys')->newEntity([
            'job_id' => $job->id,
            'name' => 'Screening',
            'instructions' => 'Please answer',
            'archived' => '9999-12-31 00:00:00',
        ]));
        $locator->get('Questions')->saveOrFail($locator->get('Questions')->newEntity([
            'survey_id' => $survey->id,
            'name' => 'Why?',
            'type' => '1',
        ]));
        $link = $locator->get('CandidatesJobs')->saveOrFail($locator->get('CandidatesJobs')->newEntity([
            'candidate_id' => $locator->get('Candidates')->saveOrFail($locator->get('Candidates')->newEntity([
                'firstname' => 'Pat',
                'lastname' => 'Candidate',
                'email' => 'pat@example.com',
                'archived' => '9999-12-31 00:00:00',
            ]))->id,
            'job_id' => $job->id,
            'archived' => '9999-12-31 00:00:00',
        ]));

        $this->get('/surveys/response/' . $survey->id . '/' . $link->id);
        $this->assertResponseOk();
        $this->assertResponseContains('Browser Survey Job');
        $this->assertResponseContains('Why?');
        $this->assertResponseContains('Respond');
    }
}
