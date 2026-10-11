<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper\HtmlHelper as CakeHtmlHelper;

class HtmlHelper extends CakeHtmlHelper
{
    public function css(array|string $path, array $options = []): ?string
    {
        return parent::css($path, $this->mapLegacyOptions($options));
    }

    public function script(array|string $url, array $options = []): ?string
    {
        return parent::script($url, $this->mapLegacyOptions($options));
    }

    protected function mapLegacyOptions(array $options): array
    {
        if (array_key_exists('inline', $options)) {
            if ($options['inline'] === false && empty($options['block'])) {
                $options['block'] = true;
            }
            unset($options['inline']);
        }

        return $options;
    }
}
