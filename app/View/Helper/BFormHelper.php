<?php

App::uses('Helper', 'View');

/**
 * Bootstrap 风格表单
 *
 * @author Lin Yang
 * @property FormHelper $Form
 *
 */
class BFormHelper extends Helper {

    var $helpers = array('Form');

    var $formatInput = '<div class="form-group"><label class="col-sm-2 control-label">%s</label>
    <div class="col-sm-10">%s</div>
    </div>';

    function create($model = null, $options = array()) {
        $options = array_merge($options, array('class'=>'form-horizontal','role'=>'form'));
        return $this->Form->create($model, $options);
    }

    function input($fieldName, $options = array()) {
        $label = isset($options['label']) ? $options['label'] : __($fieldName);
        $input = $this->Form->input($fieldName, array_merge($options, array('div'=>false, 'label'=>false,'class'=>'form-control')));
        $format = sprintf($this->formatInput, $label, $input);
        return $format;
    }

    function text($fieldName, $options = array()) {
        $label = isset($options['label']) ? $options['label'] : __($fieldName);
        $input = $this->Form->value($fieldName);
        $format = sprintf($this->formatInput, $label, $input);
        return $format;
    }
    
    public function radio($fieldName, $options = array(), $attributes = array()) {
        $label = isset($attributes['label']) ? $attributes['label'] : __($fieldName);
        $attributes['label']=false;
        $input = $this->Form->radio($fieldName, $options, $attributes);
        $format = sprintf($this->formatInput, $label, $input);
        return $format;
    }

    public function select($fieldName, $options = array(), $attributes = array()) {
        $label = isset($attributes['label']) ? $attributes['label'] : __($fieldName);
        $input = $this->Form->select($fieldName, $options, $attributes);
        $format = sprintf($this->formatInput, $label, $input);
        return $format;
    }
    

    public function checkbox($fieldName, $options = array(), $attributes = array()) {
        $label = isset($attributes['label']) ? $attributes['label'] : __($fieldName);
        $input = $this->Form->checkbox($fieldName, $options, $attributes);
        $format = sprintf($this->formatInput, $label, $input);
        return $format;
    }
    
    function submit($caption = null, $options = array()) {
        $html = $this->Form->submit($caption, array('div'=>false, 'class'=>'btn btn-primary'));
        $format = sprintf($this->formatInput, '', $html);
        return $format;
    }
}
