<?php
declare(strict_types=1);

namespace App\Test\TestCase\Auth;

use App\Auth\Cake2Sha1PasswordHasher;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;

class Cake2Sha1PasswordHasherTest extends TestCase
{
    public function testHashAndCheck(): void
    {
        Configure::write('Security.cake2Salt', 'testsalt');
        $hasher = new Cake2Sha1PasswordHasher();
        $hash = $hasher->hash('secret');
        $this->assertSame(sha1('testsaltsecret'), $hash);
        $this->assertTrue($hasher->check('secret', $hash));
        $this->assertFalse($hasher->check('other', $hash));
    }
}
