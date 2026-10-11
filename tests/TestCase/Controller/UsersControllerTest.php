<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class UsersControllerTest extends TestCase
{
    use IntegrationTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $users = TableRegistry::getTableLocator()->get('Users');
        if (!$users->find()->where(['username' => 'admin@example.com'])->first()) {
            $users->saveOrFail($users->newEntity([
                'group_id' => 1,
                'username' => 'admin@example.com',
                'password' => 'password',
                'firstname' => 'Admin',
                'lastname' => 'User',
                'is_verified' => 'Y',
                'archived' => '9999-12-31 00:00:00',
            ]));
        }
    }

    public function testLoginGet(): void
    {
        $this->get('/users/login');
        $this->assertResponseOk();
        $this->assertResponseContains('Sign In');
    }

    public function testLoginPostSuccess(): void
    {
        $this->enableCsrfToken();
        $this->post('/users/login', [
            'User' => [
                'username' => 'admin@example.com',
                'password' => 'password',
            ],
        ]);
        $this->assertRedirect();
        $this->assertSession(1, 'Auth.id');
    }

    public function testLoginPostFailure(): void
    {
        $this->enableCsrfToken();
        $this->post('/users/login', [
            'User' => [
                'username' => 'admin@example.com',
                'password' => 'wrong',
            ],
        ]);
        $this->assertResponseOk();
        $this->assertSession(null, 'Auth.id');
    }

    public function testRegisterCreatesUser(): void
    {
        $this->enableCsrfToken();
        $this->post('/users/add', [
            'User' => [
                'username' => 'newuser@example.com',
                'password' => 'secret123',
                'firstname' => 'New',
                'lastname' => 'User',
                'hint' => 'hint',
            ],
        ]);
        $this->assertRedirect(['action' => 'login']);
        $users = TableRegistry::getTableLocator()->get('Users');
        $this->assertNotNull($users->find()->where(['username' => 'newuser@example.com'])->first());
    }
}
