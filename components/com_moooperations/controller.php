<?php
namespace Moo\Operations;


class Controller extends \JController
{
    private $_input;

    private $_params;

    public function __construct(\JInput $input, \JRegistry $params)
    {
        $this->_input  = $input;
        $this->_params = $params;
    }

    public function correctViewName(array $available_names, $default_view_name)
    {
        $input_view = $this->_input->get('view', null, 'STRING');

        if (!in_array($input_view, $available_names)) {
            $input_view = $default_view_name;
            $this->_input->set('view', $input_view);
        }

        return $input_view;
    }
}