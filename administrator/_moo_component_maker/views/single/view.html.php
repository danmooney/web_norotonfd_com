<?php

MooHelper::restrictAccess();

class MooViewSingle extends JView
{
    public $id = NULL;
    public $row;
    public $arr_current_page;
    public $fields;
    public $image;
    private $debug;

    /**
     * List of states by abbreviation.  Used for HTML selects
     * @var array
     */
    public $state_list = array(
        'AL',
        'AK',  
        'AZ',  
        'AR',  
        'CA',  
        'CO',  
        'CT',  
        'DE',  
        'DC',  
        'FL',  
        'GA',  
        'HI',  
        'ID',  
        'IL',  
        'IN',  
        'IA',  
        'KS',  
        'KY',  
        'LA',  
        'ME',  
        'MD',  
        'MA',
        'MI',
        'MN',
        'MS',
        'MO',
        'MT',
        'NE',
        'NV',
        'NH',
        'NJ',
        'NM',
        'NY',
        'NC',
        'ND',
        'OH',
        'OK',
        'OR',
        'PA',
        'RI',
        'SC',
        'SD',
        'TN',
        'TX',
        'UT',
        'VT',
        'VA',
        'WA',
        'WV',
        'WI',
        'WY'
    );
    
    public $country_list = array (
        225 => "United States",
        0 => "Afghanistan",
        1 => "Albania",
        2 => "Algeria",
        3 => "American Samoa",
        4 => "Andorra",
        5 => "Angola",
        6 => "Anguilla",
        7 => "Antarctica",
        8 => "Antigua and Barbuda",
        9 => "Argentina",
        10 => "Armenia",
        11 => "Aruba",
        12 => "Australia",
        13 => "Austria",
        14 => "Azerbaijan",
        15 => "Bahamas",
        16 => "Bahrain",
        17 => "Bangladesh",
        18 => "Barbados",
        19 => "Belarus",
        20 => "Belgium",
        21 => "Belize",
        22 => "Benin",
        23 => "Bermuda",
        24 => "Bhutan",
        25 => "Bolivia",
        26 => "Bosnia and Herzegovina",
        27 => "Botswana",
        28 => "Bouvet Island",
        29 => "Brazil",
        30 => "British Indian Ocean Territory",
        31 => "Brunei Darussalam",
        32 => "Bulgaria",
        33 => "Burkina Faso",
        34 => "Burundi",
        35 => "Cambodia",
        36 => "Cameroon",
        37 => "Canada",
        38 => "Cape Verde",
        39 => "Cayman Islands",
        40 => "Central African Republic",
        41 => "Chad",
        42 => "Chile",
        43 => "China",
        44 => "Christmas Island",
        45 => "Cocos (Keeling) Islands",
        46 => "Colombia",
        47 => "Comoros",
        48 => "Congo",
        49 => "Congo, the Democratic Republic of the",
        50 => "Cook Islands",
        51 => "Costa Rica",
        52 => "Cote D'Ivoire",
        53 => "Croatia",
        54 => "Cuba",
        55 => "Cyprus",
        56 => "Czech Republic",
        57 => "Denmark",
        58 => "Djibouti",
        59 => "Dominica",
        60 => "Dominican Republic",
        61 => "Ecuador",
        62 => "Egypt",
        63 => "El Salvador",
        64 => "Equatorial Guinea",
        65 => "Eritrea",
        66 => "Estonia",
        67 => "Ethiopia",
        68 => "Falkland Islands (Malvinas)",
        69 => "Faroe Islands",
        70 => "Fiji",
        71 => "Finland",
        72 => "France",
        73 => "French Guiana",
        74 => "French Polynesia",
        75 => "French Southern Territories",
        76 => "Gabon",
        77 => "Gambia",
        78 => "Georgia",
        79 => "Germany",
        80 => "Ghana",
        81 => "Gibraltar",
        82 => "Greece",
        83 => "Greenland",
        84 => "Grenada",
        85 => "Guadeloupe",
        86 => "Guam",
        87 => "Guatemala",
        88 => "Guinea",
        89 => "Guinea-Bissau",
        90 => "Guyana",
        91 => "Haiti",
        92 => "Heard Island and Mcdonald Islands",
        93 => "Holy See (Vatican City State)",
        94 => "Honduras",
        95 => "Hong Kong",
        96 => "Hungary",
        97 => "Iceland",
        98 => "India",
        99 => "Indonesia",
        100 => "Iran, Islamic Republic of",
        101 => "Iraq",
        102 => "Ireland",
        103 => "Israel",
        104 => "Italy",
        105 => "Jamaica",
        106 => "Japan",
        107 => "Jordan",
        108 => "Kazakhstan",
        109 => "Kenya",
        110 => "Kiribati",
        111 => "Korea, Democratic People's Republic of",
        112 => "Korea, Republic of",
        113 => "Kuwait",
        114 => "Kyrgyzstan",
        115 => "Lao People's Democratic Republic",
        116 => "Latvia",
        117 => "Lebanon",
        118 => "Lesotho",
        119 => "Liberia",
        120 => "Libyan Arab Jamahiriya",
        121 => "Liechtenstein",
        122 => "Lithuania",
        123 => "Luxembourg",
        124 => "Macao",
        125 => "Macedonia, the Former Yugoslav Republic of",
        126 => "Madagascar",
        127 => "Malawi",
        128 => "Malaysia",
        129 => "Maldives",
        130 => "Mali",
        131 => "Malta",
        132 => "Marshall Islands",
        133 => "Martinique",
        134 => "Mauritania",
        135 => "Mauritius",
        136 => "Mayotte",
        137 => "Mexico",
        138 => "Micronesia, Federated States of",
        139 => "Moldova, Republic of",
        140 => "Monaco",
        141 => "Mongolia",
        142 => "Montserrat",
        143 => "Morocco",
        144 => "Mozambique",
        145 => "Myanmar",
        146 => "Namibia",
        147 => "Nauru",
        148 => "Nepal",
        149 => "Netherlands",
        150 => "Netherlands Antilles",
        151 => "New Caledonia",
        152 => "New Zealand",
        153 => "Nicaragua",
        154 => "Niger",
        155 => "Nigeria",
        156 => "Niue",
        157 => "Norfolk Island",
        158 => "Northern Mariana Islands",
        159 => "Norway",
        160 => "Oman",
        161 => "Pakistan",
        162 => "Palau",
        163 => "Palestinian Territory, Occupied",
        164 => "Panama",
        165 => "Papua New Guinea",
        166 => "Paraguay",
        167 => "Peru",
        168 => "Philippines",
        169 => "Pitcairn",
        170 => "Poland",
        171 => "Portugal",
        172 => "Puerto Rico",
        173 => "Qatar",
        174 => "Reunion",
        175 => "Romania",
        176 => "Russian Federation",
        177 => "Rwanda",
        178 => "Saint Helena",
        179 => "Saint Kitts and Nevis",
        180 => "Saint Lucia",
        181 => "Saint Pierre and Miquelon",
        182 => "Saint Vincent and the Grenadines",
        183 => "Samoa",
        184 => "San Marino",
        185 => "Sao Tome and Principe",
        186 => "Saudi Arabia",
        187 => "Senegal",
        188 => "Serbia and Montenegro",
        189 => "Seychelles",
        190 => "Sierra Leone",
        191 => "Singapore",
        192 => "Slovakia",
        193 => "Slovenia",
        194 => "Solomon Islands",
        195 => "Somalia",
        196 => "South Africa",
        197 => "South Georgia and the South Sandwich Islands",
        198 => "Spain",
        199 => "Sri Lanka",
        200 => "Sudan",
        201 => "Suriname",
        202 => "Svalbard and Jan Mayen",
        203 => "Swaziland",
        204 => "Sweden",
        205 => "Switzerland",
        206 => "Syrian Arab Republic",
        207 => "Taiwan",
        208 => "Tajikistan",
        209 => "Tanzania, United Republic of",
        210 => "Thailand",
        211 => "Timor-Leste",
        212 => "Togo",
        213 => "Tokelau",
        214 => "Tonga",
        215 => "Trinidad and Tobago",
        216 => "Tunisia",
        217 => "Turkey",
        218 => "Turkmenistan",
        219 => "Turks and Caicos Islands",
        220 => "Tuvalu",
        221 => "Uganda",
        222 => "Ukraine",
        223 => "United Arab Emirates",
        224 => "United Kingdom",

        226 => "United States Minor Outlying Islands",
        227 => "Uruguay",
        228 => "Uzbekistan",
        229 => "Vanuatu",
        230 => "Venezuela",
        231 => "Vietnam",
        232 => "Virgin Islands, British",
        233 => "Virgin Islands, U.S.",
        234 => "Wallis and Futuna",
        235 => "Western Sahara",
        236 => "Yemen",
        237 => "Zambia",
        238 => "Zimbabwe"
    );
    
    public function __construct()
    {
        parent::__construct();
        $arr_current_page = MooConfig::get('arr_current_page');
        $this->fields     = $arr_current_page['view']['single'];

        spl_autoload_register(array('MooViewSingle', 'autoloadFormatter'));
    }
    
    public function display($tpl = null)
    {
        $document =& JFactory::getDocument();
        $document->addStylesheet(JURI::base() . 'components/' . MooConfig::get('option') . '/assets' . '/css' . '/general_override.css');
        $document->addStylesheet(JURI::base() . 'components/' . MooConfig::get('option') . '/assets' . '/css' . '/plus_minus.css');
        $document->addScript(JURI::base() . 'components/' . MooConfig::get('option') . '/assets' . '/js'  . '/jquery-1.7.1-min.js');
        $document->addScript('http://maps.google.com/maps/api/js?sensor=false');
        $document->addScriptDeclaration('jQuery.noConflict();');
        $document->addScript(JURI::base() . 'components/' . MooConfig::get('option') . '/assets' . '/js'  . '/plus_minus.js');
        $document->addScript(JURI::base() . 'components/' . MooConfig::get('option') . '/assets' . '/js'  . '/geocode.js');
        $this->id = array_shift(array_values(JRequest::getVar( 'cid', array(0), '', 'array' )));
        $this->row = new MooTable();
        if ($this->id) {
            $this->row->load($this->id);
            if (isset($this->row->published)) {
                $this->assignRef('published', JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $this->row->published));
            }
        }

        // finally, display!
        require_once('tmpl/default.php');
    }
    
    public function outputRows()
    {
        $rows_html = '';

        foreach ($this->fields as $name => $field) {
            $rows_html .= $this->outputRow($field, $name, @$field['formatter'] ?: 'text');
        }

        return $rows_html;
    }

    /**
     * Output table heading key
     * @param array $field
     * @param string $formatter
     * @param string $heading
     */
    public function outputHeading($field, $formatter, $heading)
    {
        $row_heading_html = '';
        if ('hidden' !== $formatter) {
            $row_heading_html = '<td width="100" align="right" class="key">' . $heading . '</td>';
        }
        return $row_heading_html;
    }

    /**
     * Output open tags for row, depending on the field and formatter
     * @param array $field
     * @param string $formatter
     */
    public function openRow($field, $formatter, $count = 0, $heading = '')
    {
        $row_open_html = '';
        if (!isset($field['multivalue'])) {
            $row_open_html .= '<tr>';
        } else {
            $row_open_html .= '<tr data-count="' . $count . '">';
        }

        $row_open_html .= $this->outputHeading($field, $formatter, $heading);

        // TODO - Why do I have to add !$this->id???? - DM - 102312

        switch ($formatter) {
            case 'boolean':
                $row_open_html .= '<td class="boolean">';
                break;
            case 'select':
                  if (!isset($field['multivalue']) /*|| !$this->id*/) {
                      $row_open_html .= '<td>';
                  }

                break;
            case 'date':
            case 'text':
            case 'textarea':
            case 'file':
            default:
                if (!isset($field['multivalue']) /*|| !$this->id*/) {
                    $row_open_html .= '<td>';
                }
                break;
        }

        return $row_open_html;
    }

    /**
     * Output close tags for row
     */
    public function closeRow()
    {
        return '</td></tr>';
    }

    /**
     * Output row based on field metadata
     * @param array $field
     * @param string $name of field
     * @param string $formatter
     * @param boolean $nestedBool
     * @param int|null $nested_count Iteration number for multivalue
     * TODO - work on removing 'if (is_null($nested_count)) {' conditionals and replace with $this->outputPlusMinus
     */
    public function outputRow($field, $name, $formatter, $nestedBool = false, $nested_count = null)
    {
        static $heading,
               $order_ref,
               $formatter_array = array (
                   'date', 'image', 'boolean', 'json', 'select', 'file', 'hidden', 'textarea', 'text'
               ),
               $multivalue_bool = true;

        if ($this->id) {
            $row =& $this->row;
        } else {
            $row = new stdClass();
        }

        $row_html = '';

        $primary_key = MooHelper::getPrimaryKey();

        if (false === $nestedBool) {
            $heading = @$field['heading']
                ? $field['heading']
                : ucwords(str_replace('_', ' ', $name));

            if (@!$field['fields']) {
                $row_html .= $this->openRow($field, $formatter, 0, $heading);
            }
        } else if (true === $nestedBool) { // the only instance where we would ever have nested fields is when they are multivalue (i.e. plus/minus buttons, refs) TODO - NOT TRUE NOW! - DM 110712
//            if (true === $multivalue_bool) {
                $field['multivalue'] = true;
//            }
        }

        if (@$field['fields'] && false === $nestedBool) {
            $count_rows = count(MooHelper::getRows($field['table'], null, 'WHERE ' . $primary_key . ' = ' . $this->id));

            if ($count_rows === 0) {
                $count_rows = 1;  // just to output the emptys
            }

            $order_ref = @$field['order'];
            $multivalue_bool = isset($field['multivalue']);
            $nested_count = 0;
            while ($nested_count < $count_rows) {
                $inner_count = 0;
                $row_html .= /*$this->openRow($field, $formatter, $nested_count, $heading)*/'<tr data-count="0">';
                $row_html .= $this->outputHeading($field, $formatter, $heading) . '<td>';
                foreach ($field['fields'] as $subfield_name => $subfield) {
                    if ($inner_count > 0) {
                        $row_html .= '<span data-name="' . $subfield_name . '" class="multifield">';
                        if (isset($subfield['heading'])) {
                            $row_html .= $subfield['heading'];
                        } else {
                            $row_html .= ucwords(str_replace('_', ' ', $subfield_name));
                        }
                        $row_html .= '</span>';
                    }
                    $row_html .= $this->outputRow($subfield, $subfield_name, @$subfield['formatter'], true, $nested_count);
                    $inner_count += 1;
                }

                if (true === $multivalue_bool) {
                    $row_html .= $this->outputPlusMinus($nested_count);
                }
                $row_html .= $this->closeRow();
                $nested_count += 1;
            }
        } else {
            if (@$field['formatter'] && !in_array($field['formatter'], $formatter_array)) { // custom
                $class_name = ucfirst($field['formatter']) . '_Formatter';
                $class = new $class_name();
                $method = $field['formatter'];

                $row_html .= $class->$method($row, $name, $field);

            } else {
                switch (@$field['formatter']) {
                    case 'date':
                        $date_format = @$field['date_format'] ? $field['date_format'] : '%Y-%m-%d';
                        $row_html .= JHTML::_('calendar', @$row->$name, $name, $name, $date_format);
                        break;

                    case 'boolean':
                        if (isset($field['table'])) {
                            $values = MooHelper::getRows($field['table'], NULL, 'WHERE ' . $field['column_ref'] . ' = ' . $this->id);
                            if (!empty($values)) {
                                $col = $field['column'];
                                $row->$name = $values[0]->$col;
                            }
                        }

                        $row_html .=  JHTML::_('select.booleanlist', $name, 'class="inputbox" style="' . @$field['additional_style'] . '"', @$row->$name);
                        break;

                    case 'json':
                        $row->$name = json_decode($row->$name);
                        $row_html .= '<td>&nbsp;</td></tr>';
                        if (!is_object($row->$name) &&
                            !is_array($row->$name)
                        ) {
                            continue;
                        }

                        foreach ($row->$name as $k => $v) {
                            $row_html .= '<tr>';
                            $row_html .= '<td width="100" align="right" class="key">';
                            $row_html .= ucwords(str_replace('_', ' ', $k));
                            $row_html .= '</td>';

                            if (is_array($v)) {
                                $v = array_filter($v);
                                $v = implode(', ', $v);
                            }
                            $row_html .= '<td class="json">' . $v . '</td>';
                        }
                        break;

                    case 'select':
                        $options = array();
                        $options[] = JHTML::_('select.option', 'NULL', 'Please Select...');
                        if (!isset($field['multivalue'])) {
                            if ('state' == $name || 'country' == $name) {
                                $list = $name . '_list';
                                foreach ($this->$list as $key => $option) {
                                    if (@$field['use_id_as_value']) {
                                        $options[] = JHTML::_('select.option', $key, $option);
                                    } else {
                                        $options[] = JHTML::_('select.option', $option, $option);
                                    }
                                }
                                $row_html .= JHTML::_('select.genericList', $options, $name, 'class="inputbox" style=" font-size:14px;' . @$field['additional_style'] . '"', 'value', 'text', @$row->$name);
                            } else {
                                if (isset($field['options'])) {
                                    foreach ($field['options'] as $key => $option) {
                                        if (@$field['use_id_as_value']) {
                                            $options[] = JHTML::_('select.option', $key, $option);
                                        } else {
                                            $options[] = JHTML::_('select.option', $option, $option);
                                        }
                                    }
                                } elseif (isset($field['table'])) {
                                    if (@$field['order']) {
                                        $order = 'ORDER BY ' . @$field['order'];
                                    } else {
                                        $order = '';
                                    }

                                    $primary_key_ref = MooHelper::getPrimaryKey($field['table']);
                                    $column = $field['column'];

                                    $option_rows_ref = MooHelper::getRows($field['table'], NULL, NULL, $order);

                                    if (isset($field['table_ref'])) {
                                        $the_right_row = new MooTable($field['table_ref']);
                                        if ($this->id) {
                                            $the_right_row->load($this->id);
                                        }
                                    } else {
                                        $the_right_row = $row;
                                    }

                                    if (!is_null($nested_count)) {
                                        $option_rows_ref = array_slice($option_rows_ref, $nested_count, 1);
                                    }

                                    foreach ($option_rows_ref as $option) {
                                        if (@$field['use_id_as_value']) {
                                            $options[] = JHTML::_('select.option', $option->$primary_key_ref, $option->$column);
                                        } else {
                                            $options[] = JHTML::_('select.option', $option->$column, $option->$column);
                                        }
                                    }
                                }
                                $row_html .= JHTML::_('select.genericList', $options, $name, 'class="inputbox" style=" font-size:14px;' . @$field['additional_style'] . '"', 'value', 'text', @$the_right_row->$name);
                            }
                        } else {    // multivalue
                            $multivalue_string = '[]';

                            if (isset($field['rows'])) {    // used for background for carousel only... really really really really really messy code
                                $primary_key_ref = MooHelper::getPrimaryKey($field['options']['table']);
                                $option_rows = $field['rows'];
                                foreach ($option_rows as $key => $option) {
                                    $options[] = JHTML::_('select.option', $key, $option);
                                }

                                $external_table_id = $field['options']['column_ref']/*MooHelper::makeSingular(MooConfig::get('current_page')) . '_id'*/;

                                if (isset($row->$primary_key)) {
                                    $option_rows_ref   = MooHelper::getRows($field['options']['table'], NULL, 'WHERE ' . $external_table_id . ' = ' . $row->$primary_key, NULL, $order_ref);
                                }

//                                debug(array (
//                                    $field['options']['table'], NULL, 'WHERE ' . $external_table_id . ' = ' . $row->$primary_key, NULL, $order_ref
//                                ));


                                if (!is_null($nested_count) && !empty($option_rows_ref)) {
                                    /*$options_row_ref*/ $option_rows_ref = array_slice($option_rows_ref, $nested_count, 1);
                                }

                                if (!empty($option_rows_ref)) {
                                    $option_row_ref = array_shift(array_values($option_rows_ref));
                                }


                                array_shift($options); // remove 'please select..'
                                $row_html .= JHTML::_(
                                    'select.genericList',
                                    $options,
                                    $name . $multivalue_string,
                                    'class="inputbox" style="font-size:14px;' . @$field['additional_style'] . '"',
                                    'value',
                                    'text',
                                    @$option_row_ref->$field['options']['column']
                                );

                            } else {
                                if (!empty($order_ref)) {
                                    $option_row_ordering = $order_ref;
                                } elseif (isset($field['options']['order'])) {
                                    $option_row_ordering = $field['options']['order'];
                                } else {
                                    $option_row_ordering = $field['options']['column'];
                                }

                                $option_row_ordering_dir = isset($field['options']['order_dir'])
                                    ? $field['options']['order_dir']
                                    : 'ASC';

                                if (isset($field['options']['order_key_ref'])) {
                                    $order_key_ref = $field['options']['order_key_ref'];
                                } else {
                                    $order_key_ref = null;
                                }

                                $option_rows = MooHelper::getRows(
                                    $field['options']['table'],
                                    NULL,
                                    NULL,
                                    'ORDER BY ' . $option_row_ordering  . ' ' . $option_row_ordering_dir,
                                    $order_key_ref
                                );

                                $primary_key_ref = MooHelper::getPrimaryKey($field['options']['table']);


                                foreach ($option_rows as $option_row) {
                                    $options[] = JHTML::_('select.option', $option_row->$primary_key_ref, $option_row->$field['options']['column']);
                                }

                                $option_row_count = 0;
                                if (@$row->$primary_key) {
                                    $external_table_id = isset($field['options']['external_table_id'])
                                        ? $field['options']['external_table_id']
                                        : MooHelper::makeSingular(MooConfig::get('current_page')) . '_id';
//                                    debug (array (
//                                       $field['options']['table_ref'], NULL, 'WHERE ' . $external_table_id . ' = ' . $row->$primary_key, NULL, $order_key_ref
//                                    ), false);

                                    $option_rows_ref   = MooHelper::getRows($field['options']['table_ref'], NULL, 'WHERE ' . $external_table_id . ' = ' . $row->$primary_key, NULL, $order_key_ref);

    //                                debug($option_rows);
//                                    debug($option_rows_ref, false);
                                    if (!is_null($nested_count)) {
                                        $option_rows_ref = array_slice($option_rows_ref, $nested_count, 1);
                                    }

                                    // order ref array based on array keys inside $option_rows
                                    // TODO - encapsulate in method
                                    if (!is_null($order_key_ref)) {
                                        $new_option_rows_ref_arr = array();
                                        foreach ($option_rows as $key => $option_row) {
                                            if (isset($option_rows_ref[$key])) {
                                                $new_option_rows_ref_arr[] = $option_rows_ref[$key];
                                            }
                                        }
                                        $option_rows_ref = $new_option_rows_ref_arr;
                                    }

                                    $option_row_id_ref_arr = array();
                                    foreach ($option_rows_ref as $option_row_ref) {
                                        if ($option_row_ref->$external_table_id != $row->$primary_key) {
                                            continue;
                                        }

                                        foreach ($option_rows as $option_row) {
//                                            debug($option_row, false);
//                                            debug($option_row_ref);
                                            if ($option_row->$field['options']['column_ref'] == $option_row_ref->$field['options']['column_ref'] &&
                                                !in_array($option_row_ref->$field['options']['column_ref'], $option_row_id_ref_arr) // option match
                                            ) {
                                                $option_row_id_ref_arr[] = $option_row_ref->$field['options']['column_ref'];
                                                $option_row_count += 1;

                                                if ($option_row_count > 1) {
                                                    $row_html .= $this->openRow($field, 'select', $option_row_count - 1, $heading);
                                                }

                                                if (is_null($nested_count)) {
                                                    $row_html .= '<td>';
                                                }

                                                $row_html .= /*'<td>' .*/ JHTML::_(
                                                    'select.genericList',
                                                    $options,
                                                    str_replace('_id_id', '_id', $name . '_id' . @$multivalue_string),
                                                    'class="inputbox" style="font-size:14px;' . @$field['additional_style'] . '"',
                                                    'value',
                                                    'text',
                                                    @$option_row_ref->$field['options']['column_ref']
                                                );
                                                if ($option_row_count == 1) {
                                                    if (is_null($nested_count)) {
                                                        $row_html .= '<div class="plus_button"></div>';
                                                        $row_html .= '<div class="minus_button"></div>';
                                                    }
                                                } else {
                                                    if (is_null($nested_count)) {
                                                        $row_html .= '<div class="minus_button" style="margin-left:60px;"></div>';
                                                    }
                                                }

                                                if ($option_row_count == 1) {
        //                                            $row_html .= '</td></tr>';
                                                }
                                            }
                                        }
                                    }
                                }
                                if ($option_row_count == 0) {
                                    if (is_null($nested_count)) {
                                        $row_html .= '<td>';
                                    }
                                    $row_html .=
                                        JHTML::_('select.genericList', $options, str_replace('_id_id', '_id', $name . '_id' . @$multivalue_string), 'class="inputbox" style="font-size:14px;'
                                            . @$field['additional_style']
                                            . '"',
                                            'value',
                                            'text', @$row->$name
                                        );
                                    if (is_null($nested_count)) {
                                        $row_html .= '<div class="plus_button"></div>';
                                        $row_html .= '<div class="minus_button"></div>';
                                    }
                                }
                            }
                        }
                        break;

                    case 'file':
                        $file_str    =  MooHelper::checkIfImage($name) ? 'Image' : 'File';
                        $this->image = @$row->$name;
                        $use_thumb_bool = isset($field['use_thumb']);
                        $readonly_bool = isset($field['readonly']);
                        if ($this->id &&
                            $file_str == 'Image' &&
                            @MooHelper::checkImage(&$row->$name, $use_thumb_bool)
                        ) {
                            $row_html .= $row->$name;
                        }
                        if (false === $readonly_bool) {
                            $row_html .= '<p>' . $file_str . ' must be under 10MB in size <br />';
                            if ($file_str == 'Image') {
                                $row_html .= $file_str . ' must be in one of the following formats: .JPG, .GIF, or .PNG</p>';
                            }
                            $row_html .= '<input type="file" name="new_' . $name . '" id="new_' . $name . '" /> ';
                        }

                        break;

                    case 'hidden':
                        if (@$field['value']) {
                            $row->$name = $field['value'];
                        }

                        $row_html .= '<input type="hidden" name="' . $name . '" value="' . @$row->$name . '" />';
                        break;

                    //text input
                    case 'textarea':
                        $editor_width = @$field['width'] ?: '50%';
                    case 'text':
                    default:
                        if (!isset($field['multivalue'])) {
                            if (@$row->$name == '0' && $name !== 'ordering') {
                                $row->$name = '';
                            }

                            if (@$field['date_format']) {
                                @$row->$name = date($field['date_format'], strtotime(@$row->$name));
                            }

                            if (@$field['readonly']) {
                                $row_html .= '<span class="readonly">' . @$row->$name . '</span>';
                            } else {
                                if ('textarea' === $formatter) {
                                    if (@$field['allow_html']) {
                                        $editor =& JFactory::getEditor();

                                        if (false === $multivalue_bool) {
                                            $row_html .= '<div class="textarea">';
                                        }

                                        $row_html .= $editor->display($name, htmlspecialchars(@$row->$name), $editor_width, '550', '75', '20');

                                        if (false === $multivalue_bool) {
                                            $row_html .= '</div>';
                                        }
                                    } else {
                                        $row_html .= '<textarea style="width:400px;height:200px;font-size:14px;" id="' . $name . '" name="' . $name . '">'
                                            . @$row->$name
                                                . '</textarea>';
                                    }
                                } else {
                                    $row_html .= '<input size="' . (
                                    @$field['size']
                                        ? $field['size']
                                        : 50
                                    )
                                        . '" name="' . $name . '" id="' . $name . '" value="' . @$row->$name . '" class="inputbox" style="font-size:14px;'
                                        . @$field['additional_style']
                                            . '" />';
                                }

                            }
                        } else {    // multivalue
                            $multivalue_str = '[]';
                            $table = $field['options']['table'];
                            $column = $field['options']['column'];
                            $order = isset($field['options']['order']) ? $field['options']['order'] : null;
                            $column_ref = $field['options']['column_ref'];
                            if (!empty($order_ref)) {
                                $ordering_str = $order_ref;
                            } else if (!is_null($order)) {
                                $ordering_str = $order;
                            } else {
                                $ordering_str = $column;
                            }

                            $rows_ref = MooHelper::getRows($table, NULL, 'WHERE ' . $column_ref . ' = ' . @$row->$primary_key, 'ORDER BY ' . $ordering_str . ' ASC');

                            $row_count = 0;

                            if (!empty($rows_ref)) {
                                if (!is_null($nested_count)) {
                                    $rows_ref = array_slice($rows_ref, $nested_count, 1);
                                }
                                foreach ($rows_ref as $row_ref) {
                                    $row_count += 1;
                                    if ($row_count > 1 && is_null($nested_count)) {
                                        $row_html .= '<tr data-count="' . ($row_count - 1) . '">';
                                        $row_html .= '<td width="100" align="right" class="key">' . $heading . '</td>';
                                    }

                                    if (is_null($nested_count)) {
                                        $row_html .= '<td>';
                                    }

                                    if (@$field['formatter'] == 'time') {
                                        $time_format = @$field['time_format'] ? $field['time_format'] : 'g:i a';
                                        $row_ref->$name = date($time_format, strtotime($row_ref->$name));
                                    }

                                    if (@!isset($row_ref->$name)) {
                                        $row_ref->$name = $row_ref->$column;
                                    }

                                    if ('textarea' === $formatter) {
                                        if (@$field['allow_html']) {
                                            $editor =& JFactory::getEditor();
                                            $row_html .= '<div class="textarea">' . $editor->display($name . $multivalue_str, htmlspecialchars(@$row_ref->$name), $editor_width, '550', '75', '20', false, $name . $nested_count) . '</div>';
                                        } else {
                                            $row_html .= '<textarea style="width:400px;height:200px;font-size:14px;" id="' . $name . '" name="' . $name . $multivalue_str . '">'
                                                . @$row_ref->$name
                                                    . '</textarea>';
                                        }
                                    } else {
                                        $row_html .= '<input size="10" name="' . $name . $multivalue_str . '" value="' . @$row_ref->$name . '" class="inputbox" style="font-size:14px;' . @$field['additional_style'] . '" />';
                                    }

                                    if ($row_count == 1) {
                                        if (is_null($nested_count)) {
                                            $row_html .= '<div class="plus_button"></div>';
                                            $row_html .= '<div class="minus_button"></div>';
                                        }

    //                                    $row_html .= '</td></tr>';
                                    } else {
                                        if (is_null($nested_count)) {
                                            $row_html .= '<div class="minus_button" style="margin-left:60px;"></div>';
                                        }
                                    }
                                }
                            } else {
                                if (is_null($nested_count)) {
                                    $row_html .= '<td>';
                                }

                                if ('textarea' === $formatter) {
                                    if (@$field['allow_html']) {
                                        $editor =& JFactory::getEditor();
                                        $row_html .= '<div class="textarea">' . $editor->display($name . $multivalue_str, htmlspecialchars(@$row_ref->$name), $editor_width, '550', '75', '20', false, $name . $nested_count) . '</div>';
                                    } else {
                                        $row_html .= '<textarea style="width:400px;height:200px;font-size:14px;" id="' . $name . '" name="' . $name . $multivalue_str .'">'
                                            . '</textarea>';
                                    }
                                } else {
                                    $row_html .= '<input size="10" name="' . $name . $multivalue_str . '" value="" class="inputbox" style="font-size:14px;' . @$field['additional_style'] . '" />';
                                }
                                if (is_null($nested_count)) {
                                    $row_html .= '<div class="plus_button"></div>';
                                    $row_html .= '<div class="minus_button"></div>';
                                }
                            }
                        }
                        break;
                }
            }
        }

        $this->checkLoadScriptsAndStylesheets(@$field);

        if (false === $nestedBool) {
            $row_html .= '</td></tr>';
        }

        return $row_html;
    }

    public function outputHiddenInput()
    {
        $option = MooConfig::get('option');
        $type   = MooConfig::get('type');
        $hidden_html = <<<HIDDEN
        <input type="hidden" name="id" value= "$this->id" />
        <input type="hidden" name="option" value="$option" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="type" value="$type" />
HIDDEN;

        if ($this->id) {
            $hidden_html .= '<input type="hidden" name="image" value="' . @$this->image . '" />';    
        }
        return $hidden_html;
    }

    public function autoloadFormatter($name)
    {
        $autoload_functions = spl_autoload_functions();
        if (!stristr($name, 'formatter')) {
            return call_user_func($autoload_functions[0], $name);
        } else {
            $name = explode('_', $name);
            $name = strtolower($name[0]);
            require_once(MOO_COMPONENT_PATH . DS . 'formatters' . DS . 'single' . DS . $name . '.php');
        }
    }

    private function checkLoadScriptsAndStylesheets($field = null)
    {
        if (is_null($field)) {
            return;
        }

        $document =& JFactory::getDocument();
        if (@$field['load_js']) {
            foreach ($field['load_js'] as $script) {
                if (stristr($script, JURI::base())) {
                    $document->addScript($script);
                } else {
                    $document->addScriptDeclaration($script);
                }
            }
        }
        if (@$field['load_css']) {
            foreach ($field['load_css'] as $stylesheet) {
                $document->addStylesheet($stylesheet);
            }
        }
    }

    /**
     * Output plus minus button
     * @param int $count row count
     * @return string
     */
    private function outputPlusMinus($count = 0)
    {
        $plus_minus_html = '';
        if ($count === 0) {
            $plus_minus_html .= '<div class="plus_button"></div>';
            $plus_minus_html .= '<div class="minus_button"></div>';
        } else {
            $plus_minus_html .= '<div class="minus_button" style="margin-left:60px;"></div>';
        }
        return $plus_minus_html;
    }
}
