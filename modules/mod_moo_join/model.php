<?php

class MooJoinModel
{
    public $field_validator_map = array(
        'first_name' => 'alpha',
        'last_name' => 'alpha',
        'state' => 'state',
        'zip' => 'zip',
        'phone_number' => 'phone',
        'email' => 'email',
        'age' => 'number',
        'occupation' => 'filled',
        'dob' => 'date',
        'birthplace' => 'filled',
        'citizen' => 'filled',
        'hp' => 'empty'
    );

    /**
     * @var JRegistry
     */
    public $params;

    public $table = '#__moo_join_submission';

    public $field_value_map = array();

    public function __construct(JInput $input, JRegistry $params)
    {
        foreach (array_keys($this->field_validator_map) as $field) {
            $this->field_value_map[$field] = trim($input->get($field, null, 'string'));
        }

        $this->params = $params;
    }
}