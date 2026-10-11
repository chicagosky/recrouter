<?php
declare(strict_types=1);

namespace App\Auth;

use Authentication\PasswordHasher\AbstractPasswordHasher;
use Cake\Core\Configure;

class Cake2Sha1PasswordHasher extends AbstractPasswordHasher
{
    public function hash(string $password): string
    {
        $salt = (string)Configure::read('Security.cake2Salt');

        return sha1($salt . $password);
    }

    public function check(string $password, string $hashedPassword): bool
    {
        if ($hashedPassword === '') {
            return false;
        }

        return hash_equals($this->hash($password), $hashedPassword);
    }
}
