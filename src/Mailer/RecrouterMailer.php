<?php
declare(strict_types=1);

namespace App\Mailer;

use Cake\Log\Log;
use Cake\Mailer\Mailer;

class RecrouterMailer extends Mailer
{
    public function sendTemplated(string $profile, string $to, string $template, array $vars): bool
    {
        try {
            $this->setTo($to)
                ->setEmailFormat('html')
                ->viewBuilder()
                ->setTemplate($template)
                ->setLayout('default');
            $this->setViewVars($vars);
            $this->setProfile($profile === 'registration' ? 'default' : $profile);
            $this->deliver();

            return true;
        } catch (\Throwable $e) {
            Log::error('Unable to send email - ' . $e->getMessage());

            return false;
        }
    }
}
