<?php
/**
 * Add list of custom fields
 * Used for Talentcomm
 */
class Fieldlist_Formatter
{
    private $_fields;
    private $_optionable_field_types = array (
        'select',
        'radio',
        'checkbox'
    );

    public function setFields($fields)
    {
        $this->_fields = $fields;
    }

    public function getFields()
    {
        return $this->_fields;
    }

    public function fieldlist($row, $name, $view_arr)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $primary_key = MooHelper::getPrimaryKey();

        $query->select(array (
            'f.title    AS form_title',
            'ffi.field_id',
            'ffi.type_id',
            'ffi.title  AS form_field_title',
            'ffit.title AS form_field_type_title',
            'ffit.alias AS form_field_type_alias',
            'ffiref.label',
            'ffiref.required',
            'ffiref.ordering'
        ))
        ->from('#__moo_form AS f')
        ->join('INNER', '#__moo_form_field_ref AS ffiref USING (form_id)')
        ->join('INNER', '#__moo_form_field AS ffi USING (field_id)')
        ->join('INNER', '#__moo_form_field_type AS ffit USING (type_id)')
        ->order('ffiref.ordering ASC')
        ->where($primary_key . ' = ' . $row->$primary_key);

        $db->setQuery($query);

        $fields = $db->loadObjectList();

        $this->setFields($fields);

        if (empty($fields)) {
            return '<span class="no-fields-msg">No fields have been assigned to this form yet.</span>' . $this->decorate(); /*'<div class="plus-button"></div></td></tr><br />' . $this->addFieldHtml();*/
        } else {
            $this->addOptionsToFields();
            return $this->decorate();
        }
    }

    /**
     * @param stdClass $field
     * @return bool
     */
    private function isOptionable(stdClass $field)
    {
        return in_array($field->form_field_type_title, $this->_optionable_field_types);
    }

    private function addOptionsToFields()
    {
        $fields = $this->getFields();

        foreach ($fields as &$field) {
            if (!$this->isOptionable($field)) {
                continue;
            }

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('title')
                  ->from('#__moo_form_field_option')
                  ->where('field_id = ' . $field->field_id)
                  ->order('ordering ASC');

            $db->setQuery($query);
            $field->options = $db->loadObjectList();
        }

        $this->setFields($fields);
    }

    /**
     * @return string
     */
    private function getFieldOptionsHtml()
    {
        static $field_options_html;

        if (!isset($field_options_html)) {
            $db = JFactory::getDBO();

            $query = $db->getQuery(true);

            $query->select(array(
                'ffi.field_id',
                'ffi.title',
            ))->from('#__moo_form_field AS ffi')
              ->order('title ASC');

            $db->setQuery($query);

            $field_types = $db->loadObjectList();

//            print_r($field_types);

            $options = array();
            $options[] = JHTML::_('select.option', 'NULL', 'Please Select...');

            foreach ($field_types as $field) {
                $options[] = JHTML::_('select.option', $field->field_id, $field->title);
            }

            $field_options_html = $options;
        }

        return $field_options_html;
    }

    private function addFieldHtml($field = '', $i)
    {
        if (empty($field)) {
            $field = new stdClass();
            $field->type_id = $field->label = $field->required = '';
            $field->field_id = 'NULL';
        }

        $html  = '';
        $html .= '<span>Label: <input class="field-label inputbox field-list-input no-float" type="text" name="fields[label][]" value="' . $field->label . '" /></span>';
//        $html .= '<span>Field type: ' . JHTML::_('select.genericList', $this->getFieldTypeOptionsHtml(), 'fields[type_id][]', 'class="field-type inputbox field-list-input no-float"', 'value', 'text', $field->type_id)  . '</span>';
        $html .= '<span>Field: ' . JHTML::_('select.genericList', $this->getFieldOptionsHtml(), 'fields[field_id][]', 'class="field-id inputbox field-list-input no-float"', 'value', 'text', $field->field_id)  . '</span>';
        $html .= '<span>Required: '   . JHTML::_('select.genericList', array(0 => 'No', 1 => 'Yes'), 'fields[required][]', 'class="field-required inputbox field-list-input no-float"', 'value', 'text', $field->required)    . '</span>';
//        $html .= '<input class="field-id" type="hidden" name="fields[field_id][]" value="' . $field->field_id . '" />';
        $html .= '<input class="field-ordering" type="hidden" name="fields[ordering][]" value="' . $i . '" />';
        $html .= '<input class="field-id" type="hidden" name="fields[field_id][]" value="' . $field->field_id . '" />';
        return $html;
    }

    private function decorate()
    {
        $fields = (array) $this->getFields();
//        print_r($fields);

        $i = 1;

        $html  = '';
        $html .= '<div class="plus-button"></div>';
        $html .= '</td></tr>'; // close row

        if (empty($fields)) {
            $fields[] = array();
        }
        $html .= '<table class="admintable">';
        foreach ($fields as $field) {
            $html .= '<tr class="field-list-row"><td width="100" align="right">Field ' . $i . '</td><td>'; // open new row
            $html .= $this->addFieldHtml($field, $i);
            $html .= '<div class="minus-button"></div>';
            $html .= '</td></tr>'; // close row
            $i += 1;
        }
        $html .= '</table>';

        return $html;
    }
}