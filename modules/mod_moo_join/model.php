<?php

class MooJoinModel
{
    public $field_validator_map = array(
        'first_name' => 'alpha',
        'last_name' => 'alpha',
        'address' => 'filled',
        'state' => 'state',
        'zip' => 'zip',
        'phone_number' => 'phone',
        'email' => 'email',
        'age' => 'number',
//        'occupation' => 'filled',
//        'dob' => 'date',
//        'birthplace' => 'filled',
//        'citizen' => 'filled',
        'hp' => 'empty'
    );

    /**
     * @var JRegistry
     */
    public $params;

    public $table = '#__moo_join_submission';

    public $field_value_map = array();

    public $formatters = array();

    public function __construct(JInput $input, JRegistry $params)
    {
        foreach (array_keys($this->field_validator_map) as $field) {
            $this->field_value_map[$field] = trim($input->get($field, null, 'string'));
        }

        $this->params = $params;

        $this->formatters['dob'] = function ($value) {
            return date('Y-m-d', strtotime($value));
        };
    }

    public function formatFields()
    {
        foreach ($this->field_value_map as $field => &$value) {
            if (in_array($field, array_keys($this->formatters))) {
                $value = $this->formatters[$field]($value);
            }
        }
    }
}