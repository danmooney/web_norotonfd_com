<?php

MooHelper::restrictAccess();

class MooViewAll extends JView
{
    public $view;
    public $rows;
    public $query;
    public $pagination;
    public $search;
    public $lists;

    public function __construct()
    {
        parent::__construct();
        spl_autoload_register(array('MooViewAll', 'autoloadFormatter'));
    }

    public function display($tpl = NULL)
    {
        $this->prepareView();
        $this->assignRefs();
        
        $state = $this->get('state');
        
        $this->lists = array();
        $lists = $this->lists;
        
        $filter_str             = MooConfig::get('current_page') . '_filter_order';
        $filter_Dir_str         = MooConfig::get('current_page') . '_filter_order_Dir';
        $lists[$filter_str]     = $state->get(MooConfig::get('current_page') . '_filter_order');
        $lists[$filter_Dir_str] = $state->get(MooConfig::get('current_page') . '_filter_order_Dir');
        $this->assignRef('lists', $lists);
        
        $document = JFactory::getDocument();
        $document->addStylesheet(JURI::base() . 'components' . DS . MooConfig::get('option') . DS . 'assets' . DS . 'css'  . DS . 'general_override.css');

        // finally, display!
        require_once('tmpl/default.php');
    }
    
    public function prepareView()
    {
        $this->view = array();
        $this->view['search_str']           = $this->get('searchString');
        $this->get('extraQueries');      
        $this->view['rows']                 = $this->get('data');
        
        $this->view['query']                = $this->toString();
        $this->view['pagination']           = $this->get('pagination');
        $this->view['search']               = $this->get('search');
        $this->view['filter_order']         = $this->get('filterOrder');
        $this->view['filter_order_Dir']     = $this->get('filterOrderDir');
        $this->view['type']                 = $this->get('type');
        $this->view['row_count']            = $this->get('total');
        $this->view['pageOrd']              = new JPagination( $this->view['row_count'], 0, $this->view['row_count'] );
        
        $arr_page = MooConfig::get('arr_current_page');
        $this->view['table_headers']        = $arr_page['view']['all']; 
    }
    
    public function assignRefs()
    {
        foreach ($this->view as $name => $ref) {
            $this->assignRef((string) $name, $ref);    
        }
    }
    
    public function outputTableHeaders()
    {
        $filter_order     = $this->view['filter_order'];
        $filter_order_Dir = $this->view['filter_order_Dir'];
        
        $table_header_html = "<thead><tr><th width='2%'><input type='checkbox' name='toggle' value='' onclick='checkAll({$this->view['row_count']});' /></th>";
        
        foreach ($this->view['table_headers'] as $name => $table_header) {
            $ordering_jhtml = '';
            $heading = @$table_header['heading'] ? ucfirst($table_header['heading']) : ucwords(str_replace('_', ' ', $name));
            $align   = @$table_header['align'] ? $table_header['align'] : 'center';
            if (!isset($table_header['width'])) {
                $table_header['width'] = '20%';
            }
            if ($name == 'ordering') {
                $ordering_jhtml = JHTML::_('grid.order', $this->view['rows']);
            }
            if (isset($table_header['sort'])) {
                $jhtml = JHTML::_('grid.sort', $heading, MooHelper::getTableNameForOrdering($name), $filter_order_Dir, $filter_order); 
                $table_header_html .= "<th style='text-align:" . $align . "' align='" . $align . "' width='" . @$table_header['width'] . "'>" 
                                   .  $jhtml
                                   .  @$ordering_jhtml
                                   .  "</th>";           
            } else {
                $table_header_html .= "<th style='text-align:" . $align . "' align='" . $align . "' width='" . @$table_header['width'] . "'>{$heading}</th>";    
            }
        } 
        
        $table_header_html .= '</tr></thead>';
        return $table_header_html;
    }
    
    public function outputTableRows()
    {
        $primary_key = MooHelper::getPrimaryKey();
        $option = MooConfig::get('option');
        $count = 0;
        $table_rows_html = '<tbody>'; 
        foreach ($this->view['rows'] as $row) {
            $checked = JHTML::_('grid.id', $count, $row->$primary_key );
            $table_rows_html .= '<tr><td align="center" width="2">' . $checked . '</td>';
            foreach ($this->view['table_headers'] as $name => $table_header) {
                $ordering_html = '';
                if (@$table_header['formatter'] == 'image') {
                    $image_width = isset($table_header['image_width'])
                        ? $table_header['image_width']
                        : null;

                    $image_height = isset($table_header['image_height'])
                        ? $table_header['image_height']
                        : null;

                    MooHelper::checkImage($row->$name, @$table_header['use_thumb'], $image_width, $image_height);
                } elseif (isset($table_header['formatter'])) { // custom
                    $class_name = ucfirst($table_header['formatter']) . '_Formatter';
                    $class = new $class_name();
                    $method = $table_header['formatter'];

                    $row->$name = $class->$method($row, $name, $table_header);

                } elseif ($name == 'published') {
                    $row->$name = JHTML::_('grid.published', $row, $count);
                } elseif ($name == 'ordering') {
                    $ordering_html .= '<div style="width:100px">'
                                   . '<div style="float:left; width:15px;">'
                                   . $this->view['pageOrd']->orderUpIcon($count, true, 'orderup', 'Move Up', true)
                                   . '</div>'
                                   . '<div style="float:left; width:15px;">'
                                   . $this->view['pageOrd']->orderDownIcon($count, $this->view['row_count'], true, 'orderdown', 'Move Down', true)
                                   . '</div>'
                                   . '<div style="float:right">'
                                   . '<input type="text" name="order[]" size="5" value="' . $row->ordering . '" class="text_area" style="text-align:center" />'
                                   . '</div>'
                                   . '</div>';
                    $row->$name = $ordering_html;
                }
                
                if (is_numeric(@$table_header['truncate'])) {
                    $row->$name = $this->truncate($row->$name, $table_header['truncate']);
                }
                
                if (@$table_header['date_format'] &&  
                    @$row->$name
                ) {
                    $row->$name = date($table_header['date_format'], strtotime($row->$name));
                }

                if (@$table_header['value_map']) {
                    $row->$name = array_key_exists($row->$name, $table_header['value_map'])
                        ?  $table_header['value_map'][$row->$name]
                        :  $row->$name;
                }
                
                if (@$table_header['strip_tags'] === true) {
                    $row->$name = strip_tags($row->$name);
                }
                
                if (@$table_header['prepend']) {
                    $row->$name = $table_header['prepend'] . $row->$name;
                }
                
                if (@$table_header['append']) {
                    $row->$name .= $table_header['append'];
                }
                
                if (@$table_header['link']) {
                    $link_start = '<a href="index.php?option=' . $option . '&task=edit&type=' . MooConfig::get('current_page') . '&cid[]=' . $row->$primary_key . '">';
                    $link_end   = '</a>';
                } elseif (@$table_header['external_link']) {
                    $link_start = '<a href="' . MooHelper::linkify(@$row->$name) . '" target="_blank">'; 
                    $link_end   = '</a>';
                } elseif (@$table_header['file_link']) {
                    $link_start = '<a href="' . JURI::root() . 'files/' . MooConfig::get('current_page') . '/' . @$row->$name . '" target="_blank">';
                    $link_end   = '</a>';
                } else {
                    $link_start = NULL;
                    $link_end   = NULL;
                }
                                    
                $align = @$table_header['align'] ? $table_header['align'] : 'center';
                $table_rows_html .= '<td align="' . $align  . '" class="' . $name . '">' . @$link_start . @$row->$name .  @$link_end . '</td>';
            }
            $table_rows_html .= '</tr>';   
            $count += 1; 
        }
            
        $table_rows_html .= '</tbody>';       
        return $table_rows_html;       
    }
    
    public function outputSearch()
    {
        $search_string = $this->view['search_str'];
        $search_html  = '<tr><td align="left" style="display:block;width:100%">';
        $search_html .= '<input type="text" name="' . $this->view['search_str'] . '" value= "' . $this->view['search'] . '" id="' . $this->view['search_str'] . '" />';
        $search_html .= '<button type="submit">Go</button>';
        $search_html .= <<<SEARCH
<button onclick="document.getElementById('$search_string').value='';this.form.submit();">Reset</button>        
SEARCH;
        $search_html .= '</td></tr>';
        return $search_html;    
    }
    
    public function outputHiddenInput()
    {
        $option   =  MooConfig::get('option');
        $order    =& $this->view['filter_order'];
        $orderDir =& $this->view['filter_order_Dir'];
        $type     =& $this->view['type'];
        
        $hidden_html = <<<HIDDEN
        <input type="hidden" name="option" value="$option" />      
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="type" value="$type" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="$order" />
        <input type="hidden" name="filter_order_Dir" value="$orderDir" />  
HIDDEN;
        
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
            require_once(MOO_COMPONENT_PATH . DS . 'formatters' . DS . 'all' . DS . $name . '.php');
        }
    }

    private function truncate($str, $max_acceptable_length)
    {
        $str = trim(strip_tags($str));

        if (strlen($str) < $max_acceptable_length) {
            return $str;
        } else {
            $arr_str = str_split($str);
            $count = 0;
            $acceptable_places_to_truncate_at = array();
            foreach ($arr_str as $char) {
                if ($char == ' ') {
                    $acceptable_places_to_truncate_at[] = $count;
                }
                if ($count == $max_acceptable_length) {
                    return substr($str, 0, array_pop($acceptable_places_to_truncate_at)) . '...';
                }
                $count += 1;
            }
        }
    }
}