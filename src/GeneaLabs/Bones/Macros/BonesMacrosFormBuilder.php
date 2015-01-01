<?php namespace GeneaLabs\Bones\Macros;

class BonesMacrosFormBuilder extends \Illuminate\Html\FormBuilder
{
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

	public function bs_selectRangeWithInterval($label, $name, $start, $end, $interval, $default = null, $attributes = [], $errors = null)
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
			$errors
		);
	}

	public function bs_select($label, $name, $list = array(), $selected = null, array $options = [], $errors = null)
	{
		return $this->wrapOutput(
			$this->select($name, $list, $selected, $options),
			$label,
			$name,
			$errors
		);
	}

	public function bs_text($label, $name, $value = null, array $options = [], $errors = null)
	{
		return $this->wrapOutput(
			$this->input('text', $name, $value, $options),
			$label,
			$name,
			$errors
		);
	}

	public function bs_password($label, $name, array $options = [], array $errors = null)
	{
		return $this->wrapOutput(
			$this->input('password', $name, '', $options),
			$label,
			$name,
			$errors
		);
	}

	public function bs_email($label, $name, $value = null, array $options = [], $errors = null)
	{
		return $this->wrapOutput(
			$this->input('email', $name, $value, $options),
			$label,
			$name,
			$errors
		);
	}

	public function bs_url($label, $name, $value = null, array $options = [], $errors = null)
	{
		return $this->wrapOutput(
			$this->input('url', $name, $value, $options),
			$label,
			$name,
			$errors
		);
	}

	public function bs_file($label, $name, array $options = [], $errors = null)
	{
		return $this->wrapOutput(
			$this->input('file', $name, null, $options),
			$label,
			$name,
			$errors
		);
	}

	public function bs_textarea($label, $name, $value = null, array $options = [], $errors = null)
	{
		return $this->wrapOutput(
			$this->textarea($name, $value, $options),
			$label,
			$name,
			$errors
		);
	}

    public function bs_submit($label = null, $value = null, array $options = [], $errors = null)
	{
		return $this->wrapOutput(
			$this->input('submit', null, $value, $options),
			$label,
			null,
			$errors
		);
	}

	protected function wrapOutput($html = '', $label = null, $name, $errors = null)
	{
		$output = $this->preHtml($label, $name, $errors);
		$output .= $html;
		$output .= $this->postHtml($name, $errors);

		return $output;
	}

	protected function preHtml($label = null, $name, $errors = null)
	{
		$html = '<div class="form-group' . (is_null($label) ? ' col-offset-sm-3' : '') . ((count($errors) > 0) ? (($errors->has($name)) ? ' has-feedback has-error' : ' has-feedback has-success') : '') . '">';
		if (! is_null($label)) {
			$html .= $this->label($name, $label, ['class' => 'control-label col-sm-3']);
		}
		$html .= '<div class="col-sm-9">';

		return $html;
	}

	protected function postHtml($name, $errors = null)
	{
		$html = '';

		if (count($errors)) {
			$html .= '<span class="glyphicon ' . ($errors->has($name))
				? ' glyphicon-remove'
				: ' glyphicon-ok' . ' form-control-feedback"></span>';
		}
		$html .= '</div>'
			. $errors->first($name, '<p class="help-block col-sm-9 col-sm-offset-3">:message</p>')
			. '</div>';

		return $html;
	}
}
