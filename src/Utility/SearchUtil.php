<?php
declare(strict_types=1);

namespace App\Utility;

class SearchUtil
{
    public static function convertToQueryString(string $searchStr = '', string $prefix = '%', string $suffix = '%'): string
    {
        $searchList = [];
        if ($searchStr !== '') {
            $searchList = preg_split(
                "/[\s,]*\\\"([^\\\"]+)\\\"[\s,]*|" . "[\s,]*'([^']+)'[\s,]*|" . "[\s,]+/",
                $searchStr,
                0,
                PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
            ) ?: [];
            foreach ($searchList as $i => $item) {
                $searchList[$i] = $prefix . $item . $suffix;
            }
        } else {
            $searchList[0] = '%';
        }

        return implode('|', $searchList);
    }
}
