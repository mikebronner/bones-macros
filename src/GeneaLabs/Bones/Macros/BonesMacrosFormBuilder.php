<?php namespace GeneaLabs\Bones\Macros;

use Collective\Html\HtmlFacade as HTML;
use Collective\Html\FormBuilder;

class BonesMacrosFormBuilder extends FormBuilder
{
	public $errors;
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

	public function bs_open(array $options = [], $errors = null, $labelWidth = 3, $fieldWidth = 9, $offset = 0)
	{
		$this->errors = $errors;
		$this->offset = $offset;
		$this->labelWidth = $labelWidth;
		$this->fieldWidth = $fieldWidth;

		return $this->open($options);
	}

	public function bs_model($model, array $options = [], $errors = null, $labelWidth = 3, $fieldWidth = 9, $offset = 0)
	{
		$this->errors = $errors;
		$this->offset = $offset;
		$this->labelWidth = $labelWidth;
		$this->fieldWidth = $fieldWidth;

		return $this->model($model, $options);
	}

	public function bs_inputGroup($label, $name, $innerHtml, array $attributes = [], $preAddonHtml = null, $postAddonHtml = null, $extraElement = null, $extraWidth = 0)
	{
		$attributesHtml = '';

		if (! array_key_exists('class', $attributes)) {
			$attributes['class'] = 'input-group';
		}

		foreach ($attributes as $key => $value) {
			if ($key == "class" && false === strpos($value, 'input-group')) {
				$value .= ' input-group';
			}

			$attributesHtml .= ' ' . $key . '="' . trim($value) . '"';
		}

		$html = '<div' . $attributesHtml . '>';
		if ($preAddonHtml) {
			$html .= '<span class="input-group-addon">' . $preAddonHtml . '</span>';
		}
		$html .= $innerHtml;
		if ($postAddonHtml) {
			$html .= '<span class="input-group-addon">' . $postAddonHtml . '</span>';
		}
		$html .= '</div>';

		return $this->wrapOutput(
			$html,
			$label,
			$name,
			$extraElement,
			$extraWidth
		);
	}

	public function bs_selectRangeWithInterval($label, $name, $start, $end, $interval, $default = null, $attributes = [], $extraElement = null, $extraWidth = 0)
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
			$extraElement,
			$extraWidth
		);
	}

	public function bs_combobox($label, $name, $list = [], $selected = null, array $options = [], $extraElement = null, $extraWidth = 0)
	{
		return $this->wrapOutput(
			$this->select($name, $list, $selected, $options),
			$label,
			$name,
			$extraElement,
			$extraWidth
		);
	}

	public function bs_select($label, $name, $list = [], $selected = null, array $options = [], $extraElement = null, $extraWidth = 0, $useLabelAsPlaceholder = false)
	{
		return $this->bs_selectWithIcons($label, $name, $list, null, $selected, $options, $extraElement, $extraWidth, $useLabelAsPlaceholder);
	}

	public function bs_selectWithIcons($label, $name, $list = [], $icons = [], $selected = null, $options = [], $extraElement = null, $extraWidth = 0, $useLabelAsPlaceholder = false)
	{
		$selected = $this->getValueAttribute($name, $selected);
		$options['id'] = $this->getIdAttribute($name, $options);
		$html = [];
		$optionList = '';
		$placeholderText = '';

		if ($useLabelAsPlaceholder) {
			$placeholderText = $label;
			$optionList = '<option data-hidden="true"></option>';
			$label = null;
		}

		if ( ! isset($options['name'])) {
			$options['name'] = $name;
		}

		if ( ! isset($options['title'])) {
			$options['title'] = $placeholderText;
		} elseif (! strpos($options['title'], $placeholderText)) {
			$options['title'] .= ' ' . $placeholderText;
		}

		if ( ! isset($options['class'])) {
			$options['class'] = 'selectpicker';
		} elseif (! strpos($options['class'], 'selectpicker')) {
			$options['class'] .= ' selectpicker';
		}

		foreach ($list as $value => $display)
		{
			$icon = $icons[$value];
			$html[] = $this->bs_getSelectOption($display, $value, $selected, $icon);
		}

		$options = $this->html->attributes($options);
		$optionList .= implode('', $html);

		$html = "<select{$options}>{$optionList}</select>";

		if (! is_null($label)) {
			$html = $this->wrapOutput(
				$html,
				$label,
				$name,
				$extraElement,
				$extraWidth
			);
		}

		return $html;
	}

	public function bs_getSelectOption($display, $value, $selected, $icon)
	{
		if (is_array($display))
		{
			return $this->bs_optionGroup($display, $value, $selected, $icon);
		}

		return $this->bs_option($display, $value, $selected, $icon);
	}

	protected function bs_optionGroup($list, $label, $selected, $icon)
	{
		$html = [];

		foreach ($list as $value => $display)
		{
			$html[] = $this->bs_option($display, $value, $selected, $icon);
		}

		return '<optgroup label="'.e($label).'">'.implode('', $html).'</optgroup>';
	}

	protected function bs_option($display, $value, $selected, $icon)
	{
		$selected = $this->getSelectedValue($value, $selected);
		$options = ['value' => e($value), 'selected' => $selected];
		if (isset($icon)) {
			$options['data-icon'] = $icon;
		}

		return '<option'.$this->html->attributes($options).'>'.e($display).'</option>';
	}

	public function bs_text($label, $name, $value = null, array $options = [], $extraElement = null, $extraWidth = 0)
	{
		return $this->wrapOutput(
			$this->input('text', $name, $value, $options),
			$label,
			$name,
			$extraElement,
			$extraWidth
		);
	}

	public function bs_password($label, $name, array $options = [], $extraElement = null, $extraWidth = 0)
	{
		return $this->wrapOutput(
			$this->input('password', $name, '', $options),
			$label,
			$name,
			$extraElement,
			$extraWidth
		);
	}

	public function bs_email($label, $name, $value = null, array $options = [], $extraElement = null, $extraWidth = 0)
	{
		return $this->wrapOutput(
			$this->input('email', $name, $value, $options),
			$label,
			$name,
			$extraElement,
			$extraWidth
		);
	}

	public function bs_url($label, $name, $value = null, array $options = [], $extraElement = null, $extraWidth = 0)
	{
		return $this->wrapOutput(
			$this->input('url', $name, $value, $options),
			$label,
			$name,
			$extraElement,
			$extraWidth
		);
	}

	public function bs_file($label, $name, array $options = [], $extraElement = null, $extraWidth = 0)
	{
		return $this->wrapOutput(
			$this->input('file', $name, null, $options),
			$label,
			$name,
			$extraElement,
			$extraWidth
		);
	}

	public function bs_textarea($label, $name, $value = null, array $options = [], $extraElement = null, $extraWidth = 0)
	{
		return $this->wrapOutput(
			$this->textarea($name, $value, $options),
			$label,
			$name,
			$extraElement,
			$extraWidth
		);
	}

	public function bs_checkbox($label, $name, $value = 1, $checked = null, array $options = [], $extraElement = null, $extraWidth = 0)
	{
		$hasExtras = (strlen($extraElement) && $extraWidth > 0);
		$fieldWidth = ($hasExtras ? $fieldWidth = $this->fieldWidth - $extraWidth : $this->fieldWidth);

		$html = '<div class="form-group"><div class="col-sm-' . $fieldWidth . ' col-sm-offset-' . $this->labelWidth . '"><div class="checkbox">';
		$html .= '<label>' . $this->checkbox($name, $value, ($checked ? 'checked' : ''), $options) . ' ' . $label . '</label></div></div>';
		if ($hasExtras) {
			$html .= '<div class="col-sm-' . $extraWidth . '">' . $extraElement . '</div>';
		}
		$html .= '</div>';

		return $html;
	}

	public function bs_switch($label, $name, $value = 1, $checked = null, array $options = [], $extraElement = null, $extraWidth = 0)
	{
		if ((array_key_exists('class', $options))
			&& (strpos($options['class'], 'switch') >= 0)) {
			$options['class'] .= ' switch';
		} else {
			$options['class'] = 'switch';
		}

		return $this->wrapOutput(
			$this->checkbox($name, $value, ($checked ? 'checked' : null), $options),
			$label,
			$name,
			$extraElement,
			$extraWidth
		);
	}

	public function bs_submit($label = null, $value = null, array $options = [], $cancelUrl = null)
	{
		$html = $this->preHtml();

		if (! is_null($cancelUrl)) {
			$html = '<div class="form-group"><div class="col-sm-' . $this->labelWidth . '">'
				. link_to($cancelUrl, 'Cancel', ['class' => 'btn btn-cancel pull-right'])
				. '</div><div class="col-sm-' . $this->fieldWidth . '">';
		}

		$html .= $this->input('submit', null, $value, $options);
		$html .= '</div></div>';

		return $html;
	}

	protected function wrapOutput($html = '', $label = null, $name, $extraElement = null, $extraWidth = 0)
	{
		$output = $this->preHtml($label, $name, $extraElement, $extraWidth);
		$output .= $html;
		$output .= $this->postHtml($name, $extraElement, $extraWidth);

		return $output;
	}

	protected function preHtml($label = null, $name = null, $extraElement = null, $extraWidth = 0)
	{
		$hasExtras = (strlen($extraElement) && $extraWidth > 0);
		$fieldWidth = ($hasExtras ? $fieldWidth = $this->fieldWidth - $extraWidth : $this->fieldWidth);
		$html = '<div class="form-group' . ((count($this->errors) > 0) ? (($this->errors->has($name)) ? ' has-feedback has-error' : ' has-feedback has-success') : '') . '">';
		$html .= $this->label($name, $label, ['class' => 'control-label col-sm-' . $this->labelWidth]);
		$html .= '<div class="col-sm-' . $fieldWidth . '">';

		return $html;
	}

	protected function postHtml($name, $extraElement = null, $extraWidth = 0)
	{
		$html = '';
		$hasExtras = (strlen($extraElement) && $extraWidth > 0);
		$fieldWidth = ($hasExtras ? $fieldWidth = $this->fieldWidth - $extraWidth : $this->fieldWidth);

		if (count($this->errors)) {
			$html .= '<span class="glyphicon ' . ($this->errors->has($name)
					? ' glyphicon-remove'
					: ' glyphicon-ok') . ' form-control-feedback"></span>';
			$html .= $this->errors->first($name, '<p class="help-block">:message</p>');
		}
	
		$html .= '</div>';
	
		if ($hasExtras) {
			$html .= '<div class="col-sm-' . $extraWidth . '">' . $extraElement . '</div>';
		}
	
		$html .= '</div>';

		return $html;
	}
}
