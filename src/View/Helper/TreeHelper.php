<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;

class TreeHelper extends Helper
{
    protected array $helpers = ['Html'];

    public function generate(array $data, array $config = []): string
    {
        if (!$data) {
            return '';
        }
        $type = $config['type'] ?? 'ul';
        $itemType = $config['itemType'] ?? 'li';
        $attrs = [];
        if (!empty($config['id'])) {
            $attrs['id'] = $config['id'];
        }
        if (!empty($config['class'])) {
            $attrs['class'] = $config['class'];
        }

        return $this->Html->tag($type, $this->renderItems($data, $config, $itemType, $type), $attrs);
    }

    protected function renderItems(array $data, array $config, string $itemType, string $type): string
    {
        $html = '';
        $model = $config['model'] ?? null;
        $element = $config['element'] ?? false;
        $alias = $config['alias'] ?? 'name';
        foreach ($data as $result) {
            $row = $model && isset($result[$model]) ? $result[$model] : $result;
            if ($element) {
                $content = $this->getView()->element($element, ['data' => $result, 'depth' => $config['depth'] ?? 0]);
            } else {
                $content = h($row[$alias] ?? '');
            }
            $children = $result['children'] ?? [];
            if ($children) {
                $content .= $this->Html->tag($type, $this->renderItems($children, $config, $itemType, $type));
            }
            $html .= $this->Html->tag($itemType, $content);
        }

        return $html;
    }
}
