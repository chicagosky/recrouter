<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;

/**
 * CakePHP 2 Session helper shim for flash rendering in ported templates.
 */
class SessionHelper extends Helper
{
    protected array $helpers = ['Flash'];

    public function check(string $name): bool
    {
        if (str_starts_with($name, 'Message.')) {
            return (bool)$this->getView()->getRequest()->getSession()->read('Flash.' . substr($name, 8));
        }

        return $this->getView()->getRequest()->getSession()->check($name);
    }

    public function flash(string $key = 'flash'): ?string
    {
        return $this->Flash->render($key);
    }

    public function read(?string $name = null): mixed
    {
        return $this->getView()->getRequest()->getSession()->read($name);
    }
}
