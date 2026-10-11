<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Response;
use Cake\I18n\DateTime;

class TagsController extends AppController
{
    public function search(?string $text = null): Response
    {
        $rows = $this->fetchTable('Tags')->find()
            ->select(['id', 'name'])
            ->where(['name LIKE' => ($text ?? '') . '%'])
            ->all();
        return $this->jsonResponse(\App\Utility\Cake2Compat::toArray($rows));
    }
}
