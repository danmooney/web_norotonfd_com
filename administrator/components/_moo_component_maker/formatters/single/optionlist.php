<?php
/**
 * Add list of options that belong to a field
 * Used for Talentcomm
 */
class Optionlist_Formatter
{
    private $_options;

    public function setOptions($options)
    {
        $this->_options = $options;
    }

    public function getOptions()
    {
        return $this->_options;
    }


    public function optionlist($row)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $primary_key = MooHelper::getPrimaryKey();

        if (!isset($row->$primary_key)) {
            $options = array();
        } else {
            $query
            ->select('*')
            ->from('#__moo_form_field_option')
            ->where($primary_key . ' = ' . $row->$primary_key)
            ->order('ordering ASC');

            $db->setQuery($query);
            $options = $db->loadObjectList();
        }

        $this->setOptions($options);

        if (empty($options)) {
            return '<span class="no-fields-msg">No options have been assigned to this field yet.</span>' . $this->decorate(); /*'<div class="plus-button"></div></td></tr><br />' . $this->addFieldHtml();*/
        } else {
            return $this->decorate();
        }
    }

    private function addOptionHtml($option = '', $i)
    {
        if (empty($option)) {
            $option = new stdClass();
            $option->title = '';
            $option->option_id = 'NULL';
        }

        $html  = '';

        $html .= '<span><input class="field-label inputbox field-list-input no-float" type="text" name="options[title][]" value="' . $option->title . '" /></span>';
        $html .= '<input class="field-id" type="hidden" name="options[option_id][]" value="' . $option->option_id . '" />';
        $html .= '<input class="field-ordering" type="hidden" name="options[ordering][]" value="' . $i . '" />';

        return $html;
    }

    private function decorate()
    {
        $options = $this->getOptions();

        $i = 1;

        $html  = '';
        $html .= '<div class="plus-button"></div>';
//        $html .= '<input type="hidden" />';
        $html .= '</td></tr><br />'; // close row

        if (empty($options)) {
            $options[] = array();
        }

        $html .= '<table class="admintable">';
        foreach ($options as $option) {
            $html .= '<tr class="field-list-row"><td width="100" align="right">Option ' . $i . '</td><td>'; // open new row
            $html .= $this->addOptionHtml($option, $i);
            $html .= '<div class="minus-button"></div>';
            $html .= '</td></tr>'; // close row
            $i += 1;
        }
        $html .= '</table>';

        return $html;
    }
}