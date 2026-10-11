<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class CandidatesJobsEmail extends Entity
{
    protected array $_accessible = [
        '*' => true,
        'id' => false,
        'from' => true,
        'to' => true,
    ];

    protected function _getFrom(): mixed
    {
        return $this->_fields['from_address'] ?? null;
    }

    protected function _setFrom(mixed $value): mixed
    {
        $this->from_address = $value;

        return $value;
    }

    protected function _getTo(): mixed
    {
        return $this->_fields['to_address'] ?? null;
    }

    protected function _setTo(mixed $value): mixed
    {
        $this->to_address = $value;

        return $value;
    }
}
