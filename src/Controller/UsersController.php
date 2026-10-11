<?php
declare(strict_types=1);

namespace App\Controller;

use App\Mailer\RecrouterMailer;
use App\Utility\Cake2Compat;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\Routing\Router;

class UsersController extends AppController
{
    protected array $publicActions = ['*'];

    public function index(): void
    {
        $this->viewBuilder()->setLayout('dashboard');
        $users = $this->fetchTable('Users')->find('active')->contain(['Groups'])->all();
        $this->setCake2('users', $users);
        $this->set('viewTitle', 'Users');
        $this->set('addButtonTitle', 'Invite Users');
        $this->set('addButtonLink', Router::url('/users/invite', true));
        $this->set('groups', $this->fetchTable('Groups')->find('list')->toArray());
    }

    public function view(?string $id = null): void
    {
        $this->viewBuilder()->setLayout('dashboard');
        $this->set('viewTitle', 'My Info');
        if ($id === null) {
            return;
        }
        $user = $this->fetchTable('Users')->get($id, contain: ['Groups']);
        $this->setCake2('user', $user);
    }

    public function add(): ?Response
    {
        $users = $this->fetchTable('Users');
        if ($this->request->is('post')) {
            $data = $this->request->getData('User') ?? $this->request->getData();
            $entity = $users->newEntity($data);
            $entity->is_verified = 'N';
            $entity->verify_code = md5(uniqid((string)rand(), true));
            $entity->group_id = $entity->group_id ?: 1;
            if ($users->save($entity)) {
                $verifyLink = Router::url('/users/verify/' . $entity->id . '/' . $entity->verify_code, true);
                (new RecrouterMailer())->sendTemplated('registration', $entity->username, 'verify_registration', [
                    'firstname' => $entity->firstname,
                    'lastname' => $entity->lastname,
                    'verifyLink' => $verifyLink,
                    'fromUsername' => '',
                ]);
                $this->Flash->success(__('The user has been saved'));

                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error(__('There was an error creating the user account. Please try again'));

            return $this->redirect(['action' => 'add']);
        }

        return null;
    }

    public function invite(): ?Response
    {
        $this->viewBuilder()->setLayout('dashboard');
        $this->set('viewTitle', 'Users');
        $this->set('groups', $this->fetchTable('Groups')->find('list')->toArray());
        $users = $this->fetchTable('Users');
        if ($this->request->is('post')) {
            $data = $this->request->getData('User') ?? $this->request->getData();
            $entity = $users->newEntity($data);
            $entity->is_verified = 'N';
            $entity->verify_code = md5(uniqid((string)rand(), true));
            $entity->password = $entity->password ?: bin2hex(random_bytes(8));
            if ($users->save($entity)) {
                $verifyLink = Router::url('/users/invite/' . $entity->id . '/' . $entity->verify_code, true);
                (new RecrouterMailer())->sendTemplated('registration', $entity->username, 'verify_invite', [
                    'firstname' => $entity->firstname,
                    'lastname' => $entity->lastname,
                    'verifyLink' => $verifyLink,
                    'fromUsername' => $this->request->getData('from_username') ?? '',
                ]);
                $this->Flash->success(__('An invitation has been sent to the user'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('An error occurred while trying to invite the user. Please try again'));
        }

        return null;
    }

    public function verify(string $userId, string $code): Response
    {
        $users = $this->fetchTable('Users');
        $user = $users->get($userId);
        if (strcasecmp($code, (string)$user->verify_code) === 0) {
            $user->is_verified = 'Y';
            $users->save($user);
            $this->Flash->success(__('Your verification is complete and your account is now active'));
        }

        return $this->redirect(['action' => 'login']);
    }

    public function edit(?string $id = null): ?Response
    {
        $users = $this->fetchTable('Users');
        $user = $users->get($id);
        if ($this->request->is(['post', 'put'])) {
            $data = $this->request->getData('User') ?? $this->request->getData();
            $user = $users->patchEntity($user, $data);
            if ($users->save($user)) {
                $this->Flash->success(__('The changes have been saved'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('An error occurred while trying to save the changes. Please try again'));
        }

        return null;
    }

    public function delete(?string $id = null): Response
    {
        $users = $this->fetchTable('Users');
        if ($users->archive((int)$id)) {
            $this->Flash->success(__('The user has been deleted'));
        } else {
            $this->Flash->error(__('An error occurred while deleting the user. Please try again'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function forgot(): ?Response
    {
        if ($this->request->is('post')) {
            $username = (string)$this->request->getData('username');
            $user = $this->fetchTable('Users')->find()->where(['username' => $username])->first();
            if (!$user) {
                $this->Flash->error(__('Username not found'));

                return $this->redirect(['action' => 'login']);
            }
            $user->verify_code = md5(uniqid((string)rand(), true));
            $this->fetchTable('Users')->save($user);
            $resetLink = Router::url('/users/reset/' . $user->id . '/' . $user->verify_code, true);
            $this->Flash->success(__('Your password has been reset and an email has been sent.'));
            (new RecrouterMailer())->sendTemplated('registration', $username, 'reset_password', [
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'resetLink' => $resetLink,
            ]);

            return $this->redirect(['action' => 'login']);
        }

        return null;
    }

    public function reset(string $userId, ?string $code = null): ?Response
    {
        $users = $this->fetchTable('Users');
        $user = $users->get($userId);
        if ($this->request->is('post')) {
            $user->is_verified = 'Y';
            $password = $this->request->getData('password') ?? $this->request->getData('User.password');
            $hint = $this->request->getData('hint') ?? $this->request->getData('User.hint');
            if ($password) {
                $user->password = $password;
            }
            if ($hint !== null) {
                $user->hint = $hint;
            }
            $users->save($user);
            $this->Flash->success(__('Your password has been reset successfully. Please login with your new password'));

            return $this->redirect(['action' => 'login']);
        }
        if (strcasecmp((string)$code, (string)$user->verify_code) === 0) {
            $this->set('userId', $userId);
            $this->set('username', $user->username);
        }

        return null;
    }

    public function login(): ?Response
    {
        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $identity = $this->Authentication->getIdentity();
            $plain = $this->request->getData('password');
            if ($identity && is_string($plain) && $plain !== '') {
                $users = $this->fetchTable('Users');
                $user = $users->get($identity->getIdentifier());
                if ((new DefaultPasswordHasher())->needsRehash((string)$user->password)) {
                    $user->password = $plain;
                    $users->save($user);
                }
            }
            $redirect = $this->request->getQuery('redirect') ?: ['controller' => 'Jobs', 'action' => 'index'];

            return $this->redirect($redirect);
        }
        if ($this->request->is('post')) {
            $this->Flash->error(__('The username or password you entered was incorrect. Please try again'), ['key' => 'auth']);
        }

        return null;
    }

    public function logout(): Response
    {
        $this->Authentication->logout();
        $this->Flash->success(__('You have been successfully logged out'));

        return $this->redirect(['action' => 'login']);
    }

    public function doesExist(): Response
    {
        $isAvailable = true;
        if ($this->request->is('post')) {
            $username = $this->request->getData('username');
            if ($this->fetchTable('Users')->find()->where(['username' => $username])->first()) {
                $isAvailable = false;
            }
        }

        return $this->jsonResponse(['valid' => $isAvailable]);
    }

    public function toggleFavoriteJob(string $jobId): Response
    {
        $userId = $this->authUser('id');
        $table = $this->fetchTable('FavoriteJobs');
        $favorite = $table->find()->where(['user_id' => $userId, 'job_id' => $jobId])->first();
        if ($favorite) {
            $table->delete($favorite);

            return $this->jsonResponse(['success' => true]);
        }
        $entity = $table->newEntity(['user_id' => $userId, 'job_id' => $jobId]);

        return $this->jsonResponse(['success' => (bool)$table->save($entity)]);
    }

    public function toggleFavoriteCandidate(string $candidateId): Response
    {
        $userId = $this->authUser('id');
        $table = $this->fetchTable('FavoriteCandidates');
        $favorite = $table->find()->where(['user_id' => $userId, 'candidate_id' => $candidateId])->first();
        if ($favorite) {
            $table->delete($favorite);

            return $this->jsonResponse(['success' => true]);
        }
        $entity = $table->newEntity(['user_id' => $userId, 'candidate_id' => $candidateId]);

        return $this->jsonResponse(['success' => (bool)$table->save($entity)]);
    }

    public function listOfUsers(): Response
    {
        $users = $this->fetchTable('Users')->find()
            ->select(['id', 'firstname', 'lastname'])
            ->all()
            ->map(fn($u) => ['id' => $u->id, 'firstname' => $u->firstname, 'lastname' => $u->lastname])
            ->toList();

        return $this->jsonResponse($users);
    }
}
