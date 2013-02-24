<?php

class MooJoinProcessor
{
    /**
     * @var MooJoinValidator
     */
    private $_validator;

    public function __construct(MooJoinValidator $validator)
    {
        $this->_validator = $validator;
    }
}