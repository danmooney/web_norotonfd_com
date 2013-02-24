<?php

class MooJoinProcessor
{
    /**
     * @var MooJoinValidator
     */
    private $_validator;

    /**
     * @var MooJoinModel
     */
    private $_model;

    /**
     * @param MooJoinModel $model
     * @param MooJoinValidator $validator
     */
    public function __construct(MooJoinModel $model, MooJoinValidator $validator)
    {
        $this->_validator = $validator;
        $this->_model = $model;
        $this->_validator->validator_map = $model->field_validator_map;
    }

    public function validate()
    {
        return $this->_validator->validate($this->_model->field_value_map);
    }
}