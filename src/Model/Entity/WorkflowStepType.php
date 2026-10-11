<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class WorkflowStepType extends Entity
{
    public const STEP_TYPES = [
        '1' => ['name' => 'link', 'icon' => 'glyphicon glyphicon-link'],
        '2' => ['name' => 'hire', 'icon' => 'glyphicon glyphicon-thumbs-up'],
        '3' => ['name' => 'reject', 'icon' => 'glyphicon glyphicon-thumbs-down'],
        '4' => ['name' => 'notify', 'icon' => 'glyphicon glyphicon-envelope'],
        '5' => ['name' => 'assess', 'icon' => 'glyphicon glyphicon-list-alt'],
        '7' => ['name' => 'verify', 'icon' => 'glyphicon glyphicon-search'],
        '8' => ['name' => 'process', 'icon' => 'glyphicon glyphicon-cog'],
    ];

    public const STEP_TYPE_LINK = 1;
    public const STEP_TYPE_HIRE = 2;
    public const STEP_TYPE_REJECT = 3;
    public const STEP_TYPE_NOTIFY = 4;
    public const STEP_TYPE_ASSESS = 5;
    public const STEP_TYPE_VERIFY = 7;
    public const STEP_TYPE_PROCESS = 8;

    protected array $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
