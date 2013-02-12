<?php

MooHelper::restrictAccess();

class MooModelAll extends JModel
{
    private $_data;
    private $_pagination;
    private $_total;
    private $_search;
    private $_query;
    private $filter_order;
    private $filter_order_Dir;
    private $search_str;
    private $where_fields;
    
    public function __construct()
    {
        parent::__construct();
        // NOTE: JOOMLA! sometimes removes hash tag... STUPID!
        $this->filter_order      = '#' . MooHelper::getUserState(
            'filter_order',
            MooHelper::getDefault('ordering_field',
            NULL,
            MooHelper::getTableNameForOrdering(MooHelper::getPrimaryKey()) /*MooHelper::getCurrentTable() . '.' . MooHelper::getPrimaryKey()*/),
            'cmd'
        );

        if (!stristr($this->filter_order, '#__')) {
            $this->filter_order = str_replace('#', '', $this->filter_order);
        }

//        $this->filter_order = 'i.image_id';

        $this->filter_order_Dir  =       MooHelper::getUserState('filter_order_Dir',  MooHelper::getDefault('order', NULL, 'asc'), 'word');
        $this->search_str        =       MooConfig::get('current_page') . '_search';
        $this->where_fields      =       MooHelper::populateWhereFields();
        
        $this->setState(MooConfig::get('current_page') . '_filter_order',     $this->filter_order);
        $this->setState(MooConfig::get('current_page') . '_filter_order_Dir', $this->filter_order_Dir);
    }
    
    public function buildContentOrderBy() 
    {
        $orderBy = '';
        
        if (!empty($this->filter_order) && 
            !empty($this->filter_order_Dir) && 
            !$this->fieldExistsInExtraQueries($this->filter_order)
        ) {
            $orderBy = ' ORDER BY ' . $this->filter_order . ' ' . $this->filter_order_Dir;
            $orderBy = str_replace('##', '#', $orderBy);
            
            $orderBy = preg_replace('/[#]{1}([^_])/', '$1', $orderBy);
        }
        return $orderBy;
    }
    
    public function buildSearch()
    {
        if (!$this->_query) {
            $search = $this->getSearch();
            $this->_query = 
                  'SELECT '  . (MooHelper::createSelects() ? MooHelper::createSelects() : '*') . ' ' 
                . 'FROM ' . MooHelper::getCurrentTable()  . ' ' . MooHelper::getAlias() . ' '
                . MooHelper::createJoins();
            
            $arr_current_page = MooConfig::get('arr_current_page');
            
            if (isset($arr_current_page['model']['where'])) {
                $defined_where_clause = true;
                $this->_query .= ' ' . $arr_current_page['model']['where'];
            }    
            
            if (!empty($search) && 
                !empty($this->where_fields)
            ) {
                if (true === true) { // use HAVING
                    $this->_query .= MooHelper::createGroupBy();

                    $having = array();
                    $search = $this->_db->getEscaped( $search, true );
                    foreach ($this->where_fields as $field) {
                        $having[] = $field . " LIKE '%{$search}%'";
                    }

                    $this->_query .= ' HAVING ' . implode( ' OR ', $having );
                    $this->_query .= $this->buildContentOrderBy();
                } else {
                    $where = array();
                    $search = $this->_db->getEscaped( $search, true );
                    foreach ($this->where_fields as $field) {
                        $where[] = $field . " LIKE '%{$search}%'";
                    }

                    if (@$defined_where_clause) {
                        $this->_query .= ' AND (' . implode( ' OR ', $where ) . ')';
                    } else {
                        $this->_query .= ' WHERE ' . implode( ' OR ', $where );
                    }
                    $this->_query .= MooHelper::createGroupBy();
                    $this->_query .= $this->buildContentOrderBy();
                }
            } else {
                $this->_query .= MooHelper::createGroupBy();
                $this->_query .= $this->buildContentOrderBy();
            }
        }
//        echo $this->filter_order . '<br />';
//        echo $this->filter_order_Dir . '<br />';
//        debug($this->_query);
        return $this->_query;
    }
    
    public function getType()
    {
        return MooConfig::get('current_page');
    }
    
    public function getSearchString()
    {
        return $this->search_str;
    }
    
    public function getFilterOrder()
    {
        return $this->filter_order;
    }
    
    public function getFilterOrderDir()
    {
        return $this->filter_order_Dir;
    }
    
    public function getSearch()
    {
        if (!$this->_search) {
            $this->_search = JString::strtolower(MooHelper::getUserState($this->search_str, '', 'string' )); 
        }
        return $this->_search;    
    }
    
    public function getTotal() 
    {
        if (!$this->_total) {
            $query = $this->buildSearch();
            $this->_total = $this->_getListCount($query);
        }
        
        return $this->_total;
    }
    
    public function getPagination() {
        $app = JFactory::getApplication();
        if (!$this->_pagination){
            // Get pagination request variables
            $limit       = MooHelper::getUserState('limit', $app->getCfg('list_limit'), 'int');
            $limit_start = MooHelper::getUserState('limitstart', 'limitstart', 0, 'int');
            
            // In case limit has been changed, adjust it
            $limit_start = ( $limit != 0 ? (floor($limit_start / $limit) * $limit) : 0 );
            $this->_pagination = new JPagination( $this->getTotal(), $limit_start, $limit );   
        }
        
        return $this->_pagination;
    }
    
    public function getData() 
    {
        $pagination = $this->getPagination();
        if (!$this->_data) {
            $query = $this->buildSearch(); 
            $this->_data = $this->_getList( $query, $pagination->limitstart, $pagination->limit );
        }
        return $this->_data;
    }
    
    public function getExtraQueries()
    {
        $extra_queries = MooHelper::getExtraQueries();
        if (is_null($extra_queries)) {
            return false;
        }
        $db =& JFactory::getDBO();
        $pagination =& $this->getPagination();
        foreach ($extra_queries as $column => $query) {
            $query .= ' LIMIT ' . $pagination->limitstart . ', ' . $pagination->limit;
            
            $db->setQuery($query);
            $results = $db->loadObjectList();

            if (count($results) === 0) {
                return false;
            }

            $this->addExtraQueriesResultsToData($column, $results);
            if (MooHelper::getColumnName($this->filter_order) == $column) {
                $this->orderByExtraQueryColumn($column);
                if ($this->getSearch()) {
                    // TODO remove and add results based on what search string is for extra query columns
                }
            }
        }
    }

    // TODO - dynamic primary key
    private function addExtraQueriesResultsToData($column_name, $extra_results)
    {
        $data =& $this->getData();

        $primary_key = MooHelper::getPrimaryKey();

        foreach ($data as $key => $datum) {
            foreach ($extra_results as $extra_key => $extra_result) {
                if ($datum->$primary_key === $extra_result->$primary_key) {
                    $data[$key]->$column_name = $extra_result->$column_name;
                }
            }
            if (!isset($datum->$column_name)) {
                $data[$key]->$column_name = '';
            }
        }
    }
    
    private function fieldExistsInExtraQueries($field)
    {
        $arr_page = MooConfig::get('arr_current_page');
        if (stristr($field, '.')) {
            $field = explode('.', $field);
            $field = $field[1];
        }
        
        if (isset($arr_page['model']['extra_queries'][$field])) {
            return true;   
        } else {
            return false;
        }
    }
    
    private function orderByExtraQueryColumn($column)
    {
        $this->column_to_order_by = $column;
        $data = $this->getData();
        usort($data, array(__CLASS__, 'sortByColumn'));
        
        $this->_data = $data;
        $this->column_to_order_by = NULL;
    }
    
    private function sortByColumn($a, $b)
    {
        $column_to_order_by =& $this->column_to_order_by;
        
        if (strlen($a->$column_to_order_by) == 0) {
            return 1;
        } else if (strlen($b->$column_to_order_by) == 0) {
            return -1;
        }
        
        $result = strcasecmp($a->$column_to_order_by, $b->$column_to_order_by);
        if ($result != 0) {
              if ($this->filter_order_Dir == 'asc') {
                return ($result > 0) 
                    ?  1 
                    : -1;
            } else if ($this->filter_order_Dir == 'desc') {
                return ($result > 0) 
                    ? -1 
                    :  1;    
            }  
        } elseif ($result == 0) {
            // $default = MooHelper::getDefault('ordering_field', NULL, NULL);
            // if ($default) {
                // $default = MooHelper::getColumnName($default);
                // $result = strcasecmp($a->$default, $b->$default);
                // if ($this->filter_order_Dir == 'asc') {
                    // return ($result > 0) ?  1 : -1;
                // } else if ($this->filter_order_Dir == 'desc') {
                    // return ($result > 0) ? -1 :  1;    
                // }
            // }
           return 0;
        }
    }
}
