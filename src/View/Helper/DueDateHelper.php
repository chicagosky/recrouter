<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;
use DateTime;

class DueDateHelper extends Helper
{
    public function dueDateLabel(mixed $dueDate = null, array $options = []): string
    {
        if ($dueDate === null || $dueDate === '') {
            return '';
        }
        if (is_string($dueDate)) {
            $due = new DateTime($dueDate);
        } elseif ($dueDate instanceof \DateTimeInterface) {
            $due = DateTime::createFromInterface($dueDate);
        } else {
            return '';
        }
        $dueTime = (int)$due->diff(new DateTime('now'))->format('%r%a');
        $class = $options['class'] ?? '';
        if ($dueTime >= 5) {
            return "<span class=\"label label-info {$class}\">due in {$dueTime} days</span>";
        }
        if ($dueTime >= 0) {
            return "<span class=\"label label-warning {$class}\">due in {$dueTime} days</span>";
        }

        $over = abs($dueTime);

        return "<span class=\"label label-danger {$class}\">overdue by {$over} days</span>";
    }
}
