<?php namespace InvestorExperts\HTML;

use Illuminate\Html\FormBuilder as FB;

/**
 * Class FormBuilder
 * @package InvestorExperts\HTML
 */
class FormBuilder extends FB
{
    /**
     * @return string
     */
    public function cancelButton()
    {
        return '<a href="' .
            $this->url->previous() . '">' .
            $this->button('Cancel', ['class' => 'btn btn-cancel   pull-right']) . '</a>';
    }

	public function selectRangeWithInterval($name, $start, $end, $interval, $default = null, $attributes = [])
	{
		if ($interval == 0) {
			return $this->selectRange($name, $start, $end, $default, $attributes);
		}
		$items = [];
		$items[$default] = $default;
		$startValue = $start;
		$endValue = $end;
		$interval *= ($interval < 0) ? -1 : 1;
		if ($start > $end) {
			$interval *= ($interval > 0) ? -1 : 1;
			$startValue = $end;
			$endValue = $start;
		}
		for ($i=$startValue; $i<$endValue; $i+=$interval) {
			$items[$i . ""] = $i;
		}
		$items[$endValue] = $endValue;

		return $this->select($name, $items, $default, $attributes);
	}
}
