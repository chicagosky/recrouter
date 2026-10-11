<?php
declare(strict_types=1);

namespace App\Utility;

use Cake\Core\Configure;
use Cake\Log\Log;

class CPanelUtil
{
    public static function createEmailForwarder(string $username): array
    {
        if (!Configure::read('CPanel.ip') || !Configure::read('CPanel.root_user')) {
            Log::warning('CPanel credentials are not configured; skipping forwarder creation.');

            return ['success' => false, 'email' => $username . '@' . (string)Configure::read('CPanel.domain')];
        }
        require_once ROOT . DS . 'src' . DS . 'Lib' . DS . 'Legacy' . DS . 'cpanel' . DS . 'xmlapi.php';
        $xmlapi = new \xmlapi(Configure::read('CPanel.ip'));
        $xmlapi->set_port((int)Configure::read('CPanel.port'));
        $xmlapi->password_auth(Configure::read('CPanel.root_user'), Configure::read('CPanel.root_pass'));
        $xmlapi->set_output('json');
        $json = $xmlapi->api2_query(Configure::read('CPanel.root_user'), 'Email', 'addforward', [
            'email' => $username,
            'fwdopt' => 'pipe',
            'pipefwd' => Configure::read('CPanel.pipe_fwd'),
            'domain' => Configure::read('CPanel.domain'),
        ]);
        Log::debug((string)$json);
        $response = json_decode((string)$json, true) ?: [];
        if (!empty($response['cpanelresult']['event']['result'])) {
            return [
                'success' => true,
                'email' => $response['cpanelresult']['data'][0]['email'] ?? ($username . '@' . Configure::read('CPanel.domain')),
            ];
        }

        return ['success' => false];
    }
}
