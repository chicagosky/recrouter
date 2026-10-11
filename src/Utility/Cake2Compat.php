<?php
declare(strict_types=1);

namespace App\Utility;

use Cake\Datasource\EntityInterface;
use Cake\I18n\DateTime;
use Cake\Utility\Inflector;

class Cake2Compat
{
    public static function toArray(mixed $data, bool $wrap = true): mixed
    {
        if ($data === null) {
            return [];
        }
        if ($data instanceof \Traversable && !$data instanceof EntityInterface) {
            $out = [];
            foreach ($data as $item) {
                $out[] = self::toArray($item, $wrap);
            }

            return $out;
        }
        if (is_array($data)) {
            $isList = array_is_list($data);
            $out = [];
            foreach ($data as $key => $item) {
                $out[$key] = self::toArray($item, $wrap && !$isList ? false : $wrap);
            }

            return $out;
        }
        if (!$data instanceof EntityInterface) {
            if ($data instanceof DateTime || $data instanceof \DateTimeInterface) {
                return $data->format('Y-m-d H:i:s');
            }

            return $data;
        }

        $alias = Inflector::camelize(Inflector::singularize((string)$data->getSource() ?: 'Record'));
        $row = [];
        $assocs = [];
        foreach ($data->getVisible() as $field) {
            $value = $data->get($field);
            if ($field === 'children' && is_iterable($value)) {
                $assocs['children'] = self::toArray($value, true);
                continue;
            }
            if ($value instanceof EntityInterface) {
                $nested = self::toArray($value, true);
                $assocs = array_merge($assocs, $nested);
                continue;
            }
            if ($value instanceof \Traversable) {
                $items = [];
                $nestedAlias = null;
                foreach ($value as $child) {
                    if ($child instanceof EntityInterface) {
                        $nestedAlias = Inflector::camelize(Inflector::singularize((string)$child->getSource()));
                        $exported = self::toArray($child, true);
                        $items[] = $exported[$nestedAlias] ?? $exported;
                    } else {
                        $items[] = self::toArray($child, false);
                    }
                }
                if ($nestedAlias) {
                    $assocs[$nestedAlias] = $items;
                }
                continue;
            }
            if (is_array($value) && isset($value[0]) && $value[0] instanceof EntityInterface) {
                $nestedAlias = Inflector::camelize(Inflector::singularize((string)$value[0]->getSource()));
                $assocs[$nestedAlias] = [];
                foreach ($value as $child) {
                    $exported = self::toArray($child, true);
                    $assocs[$nestedAlias][] = $exported[$nestedAlias] ?? $exported;
                }
                continue;
            }
            if ($value instanceof DateTime || $value instanceof \DateTimeInterface) {
                $row[$field] = $value->format('Y-m-d H:i:s');
            } else {
                $row[$field] = $value;
            }
        }

        if ($alias === 'CandidatesJobsEmail') {
            if (array_key_exists('from_address', $row) && !array_key_exists('from', $row)) {
                $row['from'] = $row['from_address'];
            }
            if (array_key_exists('to_address', $row) && !array_key_exists('to', $row)) {
                $row['to'] = $row['to_address'];
            }
        }

        if (!$wrap) {
            return $row + $assocs;
        }

        return [$alias => $row] + $assocs;
    }
}
