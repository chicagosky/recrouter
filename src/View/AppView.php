<?php
declare(strict_types=1);

namespace App\View;

use Cake\View\View;

class AppView extends View
{
    /**
     * CakePHP 2 templates read `$this->webroot`.
     */
    public string $webroot = '/';

    public function initialize(): void
    {
        $this->webroot = (string)$this->getRequest()->getAttribute('webroot');
        $this->addHelper('Html');
        $this->addHelper('Form');
        $this->addHelper('Flash');
        $this->addHelper('Paginator');
        $this->addHelper('Time');
        $this->addHelper('Url');
        $this->addHelper('Number');
        $this->addHelper('Text');
        $this->addHelper('Tree');
        $this->addHelper('DueDate');
        $this->addHelper('Session');
    }
}
