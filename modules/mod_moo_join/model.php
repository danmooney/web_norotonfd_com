<?php

class MooJoinModel
{
    public $validators = array(
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
        'hp' => 'empty'
    );
}