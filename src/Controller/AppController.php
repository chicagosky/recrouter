<?php
declare(strict_types=1);

namespace App\Controller;

use App\Utility\Cake2Compat;
use Authentication\Controller\Component\AuthenticationComponent;
use Authorization\Controller\Component\AuthorizationComponent;
use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Cake\Http\Response;
use Cake\View\JsonView;

/**
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 * @property \Cake\Controller\Component\FlashComponent $Flash
 */
class AppController extends Controller
{
    public function viewClasses(): array
    {
        return [JsonView::class];
    }

    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');
        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Authorization.Authorization', [
            'skipAuthorization' => true,
        ]);
    }

    /**
     * Actions that do not require login. `['*']` matches CakePHP 2 Auth->allow().
     *
     * @var array<string>
     */
    protected array $publicActions = ['*'];

    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);

        $data = $this->request->getData();
        if (isset($data['data']) && is_array($data['data'])) {
            $unwrapped = $data['data'] + $data;
            unset($unwrapped['data']);
            $data = $unwrapped;
            $this->request = $this->request->withParsedBody($data);
        }
        if (isset($data['User']['username'])) {
            $data['username'] = $data['User']['username'];
            $data['password'] = $data['User']['password'] ?? ($data['password'] ?? '');
            $this->request = $this->request->withParsedBody($data);
        }

        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout('ajax');
        }

        $action = (string)$this->request->getParam('action');
        if (in_array('*', $this->publicActions, true)) {
            $this->Authentication->addUnauthenticatedActions([$action]);
        } elseif ($this->publicActions) {
            $this->Authentication->addUnauthenticatedActions($this->publicActions);
        }
    }

    public function beforeRender(EventInterface $event): void
    {
        parent::beforeRender($event);
        $this->set('wwwRoot', $this->request->getAttribute('webroot'));
        $identity = $this->request->getAttribute('identity');
        $authUser = $identity ? $identity->getOriginalData() : null;
        $this->set('authUser', $authUser);
        \AuthComponent::$current = $authUser;
    }

    protected function authUser(?string $field = null): mixed
    {
        $identity = $this->request->getAttribute('identity');
        if (!$identity) {
            return null;
        }
        if ($field === null) {
            return $identity->getOriginalData();
        }

        return $identity->get($field);
    }

    protected function setCake2(string $name, mixed $data, bool $wrap = true): void
    {
        if ($data instanceof \Cake\Datasource\Paging\PaginatedInterface) {
            $this->set('_paginated', $data);
        }
        $this->set($name, Cake2Compat::toArray($data, $wrap));
    }

    protected function jsonResponse(array $payload, int $status = 200): Response
    {
        return $this->response
            ->withType('application/json')
            ->withStatus($status)
            ->withStringBody(json_encode($payload, JSON_THROW_ON_ERROR));
    }

}
