<?php

class MooJoinValidator
{
    public $errors = array();
    public $validator_map = array();

    public function validate(array $field_value_map)
    {
        foreach ($this->validator_map as $field => $validator) {
            $validate_fn = '_validate' . ucfirst($validator);

            if (!method_exists($this, $validate_fn)) {
                trigger_error($validate_fn . ' does not exist', E_USER_WARNING);
            }

            $result = $this->$validate_fn($field_value_map[$field]);

            if (false === $result) {
                die($validate_fn . ' ' . $field . ' failed: ' . $field_value_map[$field]);
                $this->errors[] = $field;
            }
        }

        return count($this->errors) === 0;
    }

    private function _validateAlpha($value)
    {
        return (bool) preg_match('/^[\sa-z\-\.\']+$/i', $value);
    }

    private function _validateState($value)
    {
        require_once(JPATH_SITE . DS . 'modules' . DS . '_view_helper.php');

        $helper = new MooViewHelper();
        return in_array($value, $helper->states);
    }

    private function _validateZip($value)
    {
        return (bool) preg_match('/^\d{5}(-\d{4})?$/', $value);
    }

    private function _validateEmail($value)
    {
        return (bool) filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    private function _validateNumber($value)
    {
        return (bool) preg_match('/^\d+$/', $value);
    }

    private function _validateDate($value)
    {

    }

    private function _validatePhone($value)
    {
        return (bool) preg_match('/^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/', $value);
    }

    private function _validateFilled($value)
    {
        return strlen($value) > 0;
    }

    private function _validateEmpty($value)
    {
        return strlen($value) === 0;
    }
}