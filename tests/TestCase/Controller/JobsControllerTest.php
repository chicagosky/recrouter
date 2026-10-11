<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\I18n\DateTime;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class JobsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    public function testIndex(): void
    {
        $this->get('/jobs');
        $this->assertResponseOk();
    }

    public function testKeywordSearch(): void
    {
        $jobs = TableRegistry::getTableLocator()->get('Jobs');
        $job = $jobs->newEntity([
            'title' => 'UniqueSearchRole',
            'description' => 'PHP developer',
            'archived' => '9999-12-31 00:00:00',
            'user_id' => 1,
        ]);
        $jobs->saveOrFail($job);

        $this->enableCsrfToken();
        $this->post('/jobs', ['keywords' => 'UniqueSearchRole']);
        $this->assertResponseOk();
        $this->assertResponseContains('UniqueSearchRole');
    }
}
