<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper\PaginatorHelper as CakePaginatorHelper;

class PaginatorHelper extends CakePaginatorHelper
{
    public function numbers(array $options = []): string
    {
        if (isset($options['separator'])) {
            unset($options['separator']);
        }

        return parent::numbers($options);
    }

    public function sortKey(?string $model = null, array $options = []): ?string
    {
        $sort = $this->param('sort');

        return $sort !== false && $sort !== null ? (string)$sort : null;
    }

    public function sortDir(?string $model = null, array $options = []): string
    {
        $dir = $this->param('direction');

        return $dir ? strtolower((string)$dir) : 'asc';
    }
}
