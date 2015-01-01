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

	public function bs_selectRangeWithInterval($label, $name, $start, $end, $interval, $default = null, $attributes = [], array $errors = [])
	{
		$html = $this->preHtml($label, $name, $errors);
		$html .= $this->selectRangeWithInterval($name, $start, $end, $interval, $default, $attributes);
		$html .= $this->postHtml($name, $errors);

		return $html;
	}

	public function bs_text($label, $name, $value = null, array $options = [], array $errors = [])
	{
		$html = $this->preHtml($label, $name, $errors);
		$html .= $this->input('text', $name, $value, $options);
		$html .= $this->postHtml($name, $errors);

		return $html;
	}

	public function bs_password($label, $name, array $options = [], array $errors = [])
	{
		$html = $this->preHtml($label, $name, $errors);
		$html .= $this->input('password', $name, '', $options);
		$html .= $this->postHtml($name, $errors);

		return $html;
	}

	public function bs_email($label, $name, $value = null, array $options = [], array $errors = [])
	{
		$html = $this->preHtml($label, $name, $errors);
		$html .= $this->input('email', $name, $value, $options);
		$html .= $this->postHtml($name, $errors);

		return $html;
	}

	public function bs_url($label, $name, $value = null, array $options = [], array $errors = [])
	{
		$html = $this->preHtml($label, $name, $errors);
		$html .= $this->input('url', $name, $value, $options);
		$html .= $this->postHtml($name, $errors);

		return $html;
	}

	public function bs_file($label, $name, array $options = [], array $errors = [])
	{
		$html = $this->preHtml($label, $name, $errors);
		$html .= $this->input('file', $name, null, $options);
		$html .= $this->postHtml($name, $errors);

		return $html;
	}

	public function bs_textarea($label, $name, $value = null, array $options = [], array $errors = [])
	{
		$html = $this->preHtml($label, $name, $errors);
		$html .= $this->textarea($name, $value, $options);
		$html .= $this->postHtml($name, $errors);

		return $html;
	}

	protected function preHtml($label, $name, array $errors = [])
	{
		$html = '<div class="form-group' . (count($errors) > 0) ? (($errors->has($name)) ? ' has-feedback has-error' : ' has-feedback has-success') : '' . '">'
			. $this->label($label, $name, ['class' => 'control-label col-sm-3'])
            . '<div class="col-sm-9">';

		return $html;
	}

	protected function postHtml($name, array $errors = [])
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
