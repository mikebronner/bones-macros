<?php namespace GeneaLabs\Bones\Macros;

class BonesMacrosFormBuilder extends \Illuminate\Html\FormBuilder
{
	public $offset;
	public $labelWidth;
	public $fieldWidth;

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

	public function bs_open(array $options = array(), $offset = 0, $labelWidth = 3, $fieldWidth = 9)
	{
		$this->offset = $offset;
		$this->labelWidth = $labelWidth;
		$this->fieldWidth = $fieldWidth;

		return $this->open($options);
	}


	public function bs_selectRangeWithInterval($label, $name, $start, $end, $interval, $default = null, $attributes = [], $errors = null, $extraElement = null, $extraWidth = 0)
	{
		return $this->wrapOutput(
			$this->selectRangeWithInterval(
				$name,
				$start,
				$end,
				$interval,
				$default,
				$attributes
			),
			$label,
			$name,
			$errors,
			$extraElement,
			$extraWidth
		);
	}

	public function bs_select($label, $name, $list = array(), $selected = null, array $options = [], $errors = null, $extraElement = null, $extraWidth = 0)
	{
		return $this->wrapOutput(
			$this->select($name, $list, $selected, $options),
			$label,
			$name,
			$errors,
			$extraElement,
			$extraWidth
		);
	}

	public function bs_text($label, $name, $value = null, array $options = [], $errors = null, $extraElement = null, $extraWidth = 0)
	{
		return $this->wrapOutput(
			$this->input('text', $name, $value, $options),
			$label,
			$name,
			$errors,
			$extraElement,
			$extraWidth
		);
	}

	public function bs_password($label, $name, array $options = [], array $errors = null, $extraElement = null, $extraWidth = 0)
	{
		return $this->wrapOutput(
			$this->input('password', $name, '', $options),
			$label,
			$name,
			$errors,
			$extraElement,
			$extraWidth
		);
	}

	public function bs_email($label, $name, $value = null, array $options = [], $errors = null, $extraElement = null, $extraWidth = 0)
	{
		return $this->wrapOutput(
			$this->input('email', $name, $value, $options),
			$label,
			$name,
			$errors,
			$extraElement,
			$extraWidth
		);
	}

	public function bs_url($label, $name, $value = null, array $options = [], $errors = null, $extraElement = null, $extraWidth = 0)
	{
		return $this->wrapOutput(
			$this->input('url', $name, $value, $options),
			$label,
			$name,
			$errors,
			$extraElement,
			$extraWidth
		);
	}

	public function bs_file($label, $name, array $options = [], $errors = null, $extraElement = null, $extraWidth = 0)
	{
		return $this->wrapOutput(
			$this->input('file', $name, null, $options),
			$label,
			$name,
			$errors,
			$extraElement,
			$extraWidth
		);
	}

	public function bs_textarea($label, $name, $value = null, array $options = [], $errors = null, $extraElement = null, $extraWidth = 0)
	{
		return $this->wrapOutput(
			$this->textarea($name, $value, $options),
			$label,
			$name,
			$errors,
			$extraElement,
			$extraWidth
		);
	}

	public function bs_submit($label = null, $value = null, array $options = [], $errors = null, $cancelUrl = null)
	{
		$html = $this->preHtml(null, null, $errors);

		if (! is_null($cancelUrl)) {
			$html = '<div class="form-group"><div class="col-sm-' . $this->labelWidth . '">'
			        . link_to($cancelUrl, 'Cancel', ['class' => 'btn btn-cancel pull-right'])
			        . '</div><div class="col-sm-' . $this->fieldWidth . '">';
		}

		$html .= $this->input('submit', null, $value, $options);
		$html .= '</div></div>';

		return $html;
	}

	protected function wrapOutput($html = '', $label = null, $name, $errors = null, $extraElement = null, $extraWidth = 0)
	{
		$output = $this->preHtml($label, $name, $errors, $extraElement, $extraWidth);
		$output .= $html;
		$output .= $this->postHtml($name, $errors, $extraElement, $extraWidth);

		return $output;
	}

	protected function preHtml($label = null, $name = null, $errors = null, $extraElement = null, $extraWidth = 0)
	{
		$hasExtras = (strlen($extraElement) && $extraWidth > 0);
		$fieldWidth = ($hasExtras ? $fieldWidth = $this->fieldWidth - $extraWidth : $this->fieldWidth);

		$html = '<div class="form-group' . ((count($errors) > 0) ? (($errors->has($name)) ? ' has-feedback has-error' : ' has-feedback has-success') : '') . '">';
		$html .= $this->label($name, $label, ['class' => 'control-label col-sm-' . $this->labelWidth]);
		$html .= '<div class="col-sm-' . $fieldWidth . '">';

		return $html;
	}

	protected function postHtml($name, $errors = null, $extraElement = null, $extraWidth = 0)
	{
		$html = '';
		$hasExtras = (strlen($extraElement) && $extraWidth > 0);
		$fieldWidth = ($hasExtras ? $fieldWidth = $this->fieldWidth - $extraWidth : $this->fieldWidth);

		if (count($errors)) {
			$html .= '<span class="glyphicon ' . ($errors->has($name))
				? ' glyphicon-remove'
				: ' glyphicon-ok' . ' form-control-feedback"></span>';
		}
		$html .= '</div>'
			. $errors->first($name, '<p class="help-block col-sm-' . $fieldWidth . ' col-sm-offset-' . $this->labelWidth . '">:message</p>');
		if ($hasExtras) {
			$html .= '<div class="col-sm-' . $extraWidth . '">' . $extraElement . '</div>';
		}
		$html .= '</div>';

		return $html;
	}
}
