<?php
class DueDateHelper extends AppHelper {
	public function dueDateLabel($dueDate = null, $options = array()) {
		$label = '';
		if (null != $dueDate) {
			$dueTime = $dueDate->diff(new DateTime('now'))->format('%a');
			$class = (isset($options['class'])) ? $options['class'] : '';
			if ($dueTime >= 5) {
				$label = "<span class=\"label label-info ".$class."\">due in $dueTime days</span>";
			} elseif ($dueTime >= 0 && $dueTime < 5) {
				$label = "<span class=\"label label-warning ".$class."\">due in $dueTime days</span>";
			} else {
				$label = "<span class=\"label label-danger ".$class."\">overdue by $dueTime days</span>";
			}
		}
		return $label;
	}
}
?>