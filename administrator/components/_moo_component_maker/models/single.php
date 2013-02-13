<?php

MooHelper::restrictAccess();

class MooModelSingle extends JModel
{
    public $file_to_upload;
    public $id;
    public $new_file;
    public $file_name;
    public $upload_error;
    
    public function __construct()
    {
        parent::__construct();
        $this->id = JRequest::getVar('id');
    }

    public function uploadFile(&$row)
    {
        $field_name =& $this->file_to_upload;
        $field_name_without_new = substr($field_name, 4, strlen($field_name));
        $file_error =  $_FILES[$field_name]['error'];
        unset($this->upload_error);
        
        $file_str   =  MooHelper::checkIfImage($field_name_without_new) ? 'Image' : 'File';
        
        if ($file_error > 0) {
            $upload_error =& $this->upload_error;
            $upload_error = JText::_( 'There was an error uploading your ' . $file_str . '.  Please try again.' );
            return false;
        }
        
        $file_name = date('Y-m-d_H-i-s') . '-' . $this->makeSafe($_FILES[$field_name]['name']);
        $this->file_name = $file_name;
        $file_tmp        = $_FILES[$field_name]['tmp_name'];

        $arr_current_page = MooConfig::get('arr_current_page');
        $file_folder_name = isset($arr_current_page['file_folder'])
            ? $arr_current_page['file_folder']
            : MooConfig::get('current_page');

        if (isset($arr_current_page['view']['single'][$field_name_without_new]['file_folder'])) {
            $file_folder_name .= DS . $arr_current_page['view']['single'][$field_name_without_new]['file_folder'];
        }

        $target        = JPATH_SITE . DS . (strtolower($file_str) . 's') . DS . $file_folder_name . DS . $file_name;
        $folder_target = JPATH_SITE . DS . (strtolower($file_str) . 's') . DS . $file_folder_name;

        if (!file_exists($folder_target)) {
            mkdir($folder_target);
        }
        
        if (move_uploaded_file($file_tmp, $target)) {
            chmod($target, 0777);
            $this->new_file =  $file_name;
            if ($file_str == 'Image') {
                $arr_current_page  =  MooConfig::get('arr_current_page');
                $arr_image_dims    =& $arr_current_page['view']['single'][$field_name_without_new];
                
                if (isset($arr_image_dims['upload_width']) ||
                    isset($arr_image_dims['upload_height'])
                ) {
                    $resize_image = true;    
                } else {
                    $resize_image = false;
                }
                 
                if (isset($arr_image_dims['upload_thumb_width']) ||  
                    isset($arr_image_dims['upload_thumb_height'])
                ) {
                    $create_thumb = true;    
                } else {
                    $create_thumb = false;
                }
                 
                if ($resize_image || $create_thumb) {
                    $image_size = getimagesize($target);
                    require_once(MooConfig::get('wideimage_path'));
                    if ($resize_image) {
                        if (!isset($arr_image_dims['upload_height'])) {
                            $arr_image_dims['upload_height'] = round(($arr_image_dims['upload_width'] / $image_size[0]) * $image_size[1]);
                        } elseif (!isset($arr_image_dims['upload_width'])) {
                            $arr_image_dims['upload_width'] = round(($arr_image_dims['upload_height'] / $image_size[1]) * $image_size[0]);
                        }

                        WideImage::load($target)->resize($arr_image_dims['upload_width'], $arr_image_dims['upload_height'], 'outside')
                            ->crop('center', 'center',   $arr_image_dims['upload_width'], $arr_image_dims['upload_height'])
                            ->saveToFile($target);
                    }
                    
                    if ($create_thumb) {
                        $thumb_folder_target = JPATH_SITE . DS . 'images' . DS . $file_folder_name . DS . 'thumbs';
                        $thumb_target = $thumb_folder_target . DS . $file_name;
                        if (!file_exists($thumb_folder_target)) {
                            mkdir($thumb_folder_target);
                            chmod($thumb_folder_target, 0777);
                        }
                        
                        if (!isset($arr_image_dims['upload_thumb_height'])) {
                            $arr_image_dims['upload_thumb_height'] = round(($arr_image_dims['upload_thumb_width'] / $image_size[0]) * $image_size[1]);
                        } elseif (!isset($arr_image_dims['upload_thumb_width'])) {
                            $arr_image_dims['upload_thumb_width'] = round(($arr_image_dims['upload_thumb_height'] / $image_size[1]) * $image_size[0]);
                        }
                        
                        WideImage::load($target)->resize($arr_image_dims['upload_thumb_width'], $arr_image_dims['upload_thumb_height'], 'outside')
                            ->crop('center', 'center',   $arr_image_dims['upload_thumb_width'], $arr_image_dims['upload_thumb_height'])
                            ->saveToFile($thumb_target);
                            
                        if (@$arr_image_dims['grayscale_thumb']) {
                            $thumb_folder_target = JPATH_SITE . DS . 'images' . DS . MooConfig::get('current_page') . DS . 'thumbs_grayscale';
                            $thumb_target = $thumb_folder_target . DS . $file_name;
                            
                            if (!file_exists($thumb_folder_target)) {
                                mkdir($thumb_folder_target);
                                chmod($thumb_folder_target, 0777);
                            }
                            WideImage::load($target)->resize($arr_image_dims['upload_thumb_width'], $arr_image_dims['upload_thumb_height'], 'outside')
                                ->crop('center', 'center',   $arr_image_dims['upload_thumb_width'], $arr_image_dims['upload_thumb_height'])
                                ->asGrayscale()
                                ->saveToFile($thumb_target);
                        }
                    }
                }
            }

            return true;
        } else {
            $upload_error = JText::_( 'There was an issue moving the ' . $file_str . ' from the tmp folder on the server.' );
            JError::raiseError(500, $upload_error);
            return false;
        }
    }

    /**
     * Put refs in table mapping into appropriate columns in DB
     * TODO - move table mapping from controller to model
     */
    public function saveTableRefs()
    {
        $arr_current_page = MooConfig::get('arr_current_page');
        if (!isset($arr_current_page['controller']['table_mapping'])) {
            return;
        }

        $db =& JFactory::getDBO();
        $table_mapping =& $arr_current_page['controller']['table_mapping'];

        $arr_external_table = array();
        foreach ($table_mapping as $table_name_str => $values) {
            $ref = isset($arr_current_page['controller']['table_mapping'][$table_name_str]['ref'])
                 ? $arr_current_page['controller']['table_mapping'][$table_name_str]['ref']
                 : MooHelper::makeSingular(MooConfig::get('current_page')) . '_id';

            $updateBool = isset($values['update']);

            if (!$updateBool) {
                $query = 'DELETE FROM ' . $table_name_str . ' '
                    . 'WHERE ' . $ref . ' IN('
                    .
                    (
                    !is_array($this->id)
                        ? $this->id
                        : implode(', ', $this->id)
                    )
                    . ')';
                $db->setQuery($query);
                $db->query();
            }
            $multivalue = isset($values['multivalue']);
            if (!$multivalue) {
                $external_row = new MooTable($table_name_str);
                if (!$updateBool) {
                    foreach ($values as $key => $value) {
                        if (is_array($value)) {
                            $post_value = JRequest::getVar($key, '', 'post', 'string', JREQUEST_ALLOWRAW);
                            if (is_array($post_value)) {
                                $post_value = $post_value[0];
                            }

                            $external_row->$value['column'] = $post_value;
                        }
                    }
                } else {
                    // TODO - Update Bool === true
                }

                $external_row->$ref = $this->id;
                $this->convertNullStrToNull($external_row);
//                debug($external_row);
                if (!$external_row->store(false, true)) {
                    JError::raiseError(500, $external_row->getError());
                }

            } else {
                $total = count(JRequest::getVar($values['count']));
                if ($total === 0) {
                    return;
                }

                if (!$updateBool) {
                    for ($i = 0; $i < $total; $i += 1) {
                        $external_row = new MooTable($table_name_str);
                        foreach ($values as $key => $value) {
                            if (is_array($value)) { // column to store
                                if ($values['count'] == $key) { // count is the key that compares to which other values can be empty and still be stored
                                    $arr_keys = JRequest::getVar($key);
                                    if ($arr_keys[$i] == '' || $arr_keys[$i] === 'NULL') {
                                        $empty = true;
                                        continue;
                                    } else {
                                        $empty = false;
                                    }
//                                    echo 'arr_keys[$i] = ' . $arr_keys[$i] . '<br />';

                                    $external_row->$value['column'] = $arr_keys[$i];

                                    if ($key == 'time') {
                                        $external_row->$value['column'] = date('H:i:s', strtotime($arr_keys[$i]));
                                    }
                                } else {
                                    // TODO - HTML always allowed...
                                    $other_data = JRequest::getVar($value['column'], '', 'post', 'string', JREQUEST_ALLOWRAW);
                                    if ($other_data[$i] === 'NULL') {
                                        $other_data[$i] = NULL;
                                    }

                                    $external_row->$value['column'] = $other_data[$i];

                                    // TODO - empty evaluates to true if 0 or empty string... make function to check if column supports null
//                                    if (empty($other_data[$i])) {
//                                        $empty = true;
//                                    } else {
//                                        $empty = false;
//                                    }
                                }
                            }
                            $external_row->$values['ref'] = $this->id;
                        }
//                        echo $external_row->image_id . '<br />' . $external_row->carousel_id; die;
                        if (!$empty && !$external_row->store()) {
                            JError::raiseError(500, $external_row->getError());
                        }
                    }
                } else {
                    $count_values = JRequest::getVar($values['count']);
                    for ($i = 0; $i < $total; $i += 1) {
                        $external_row = new MooTable($table_name_str);

                        $current_count_value = $count_values[$i];

                        $primary_key_arr = array(
                            $ref => $this->id,
                            $values['count'] => $current_count_value
                        );

                        $external_row->load($primary_key_arr);

                        $query = $db->getQuery(true);
                        $query
                            ->update($table_name_str)
                            ->where($ref . ' = ' . $this->id . ' AND ' . $values['count'] . ' = ' . $current_count_value);

                        foreach ($values as $key => $value) {
                            if ($values['count'] === $key || !is_array($value)) {
                                continue;
                            }

                            // TODO - HTML always allowed...
                            $other_data = JRequest::getVar($value['column'], '', 'post', 'string', JREQUEST_ALLOWRAW);
                            if ($other_data[$i] === 'NULL') {
                                $other_data[$i] = NULL;
                            }

                            $query->set($value['column'] .  ' = ' . $other_data[$i]);
                        }

                        $db->setQuery($query);
                        $db->query();
                    }
                }
            }
        }
    }

    public function deleteFile($file_name)
    {
        if (empty($file_name)) {
            return;
        }
        
        $file_path = JPATH_SITE . DS . 'images' . DS . MooConfig::get('current_page') . DS . $file_name;
        unlink($file_path);
    }

    // for the purposes of not overwriting images/files
    public function removeEmptyProperties(&$row)
    {
        $arr_current_page = MooConfig::get('arr_current_page');
        $view = $arr_current_page['view']['single'];

        foreach ($row as $key => $value) {
            if (empty($value) && !is_numeric($value)) {
                if (isset($view[$key])) {
                    if (isset($view[$key]['formatter'])) {
                        if ('file' === $view[$key]['formatter']) {
                            unset($row->$key);
                        }
                    }
                }
            }
        }
    }

    public function convertNullStrtoNull(&$row) {
        $null_fields_bool = false;
        foreach ($row as $key => $value) {
            if ('NULL' === $value) {
                $row->$key = null;
                $null_fields_bool = true;
            }
        }
        return $null_fields_bool;
    }
    
    public function trimProperties(&$row)
    {
        foreach ($row as $key => $value) {
            if (is_string($row->$key)) {
                trim($row->$key);
            }
        }
    }

    /**
     * If column is allowed to have HTML, then replace with raw
     * TODO - implement with fields subarray in mind!  All HTML is allowed right now as a temporary fix.
     */
    public function addHTMLToAllowedProperties(&$row)
    {
        $arr_current_page = MooConfig::get('arr_current_page');
        $single_view_config = $arr_current_page['view']['single'];
        foreach ($row as $key => $value) {
            if (property_exists('JTable', $key) || property_exists('JObject', $key)) {
                continue;
            }
            if (is_array($value)) {
                $subvalues = JRequest::getVar($key, '', 'post', 'string', JREQUEST_ALLOWRAW);
                $row->$key = $subvalues;
            } else {
                if (!isset($single_view_config[$key]['allow_html'])) {
                    continue;
                }
                $row->$key = JRequest::getVar($key, '', 'post', 'string', JREQUEST_ALLOWRAW);
            }
        }
    }
    
    public function convertDateColumnsToMySQLFormat(&$columns)
    {
        foreach ($columns as $name => $column) {
            if (strstr($name, 'date')) {
                if ($column) {
                    $columns->$name = date('Y-m-d', strtotime($column));
                } else {
                    $columns->$name = NULL;
                } 
            }
        }
    }

    /**
     * Logic to execute before updating row values
     * @param MooTable $row
     */
    public function preHook(&$row)
    {
        $arr_current_page = MooConfig::get('arr_current_page');

        if (!isset($arr_current_page['model']['pre_hook'])) {
            return;
        }

        $pre_hook = $arr_current_page['model']['pre_hook'];
        if (!is_callable($pre_hook)) {
            throw new Exception('Pre hook is not a function');
        }

        $pre_hook($row);
    }

    /**
     * Logic to execute after row values are updated,
     * but immediately before saving
     *
     * @param MooTable $row
     */
    public function postHook(&$row)
    {
        $arr_current_page = MooConfig::get('arr_current_page');

        if (!isset($arr_current_page['model']['post_hook'])) {
            return;
        }

        $post_hook = $arr_current_page['model']['post_hook'];
        if (!is_callable($post_hook)) {
            throw new Exception('Post hook is not a function');
        }

        $post_hook($row);
    }

    public function afterSave(&$row)
    {
        $arr_current_page = MooConfig::get('arr_current_page');

        if (!isset($arr_current_page['model']['after_save'])) {
            return;
        }

        $after_save = $arr_current_page['model']['after_save'];
        if (!is_callable($after_save)) {
            throw new Exception('After save is not a function');
        }

        $after_save($row);
    }
    
    public function makeSafe($file_name)
    {
        $file_name = strtolower($file_name);
        $file_name = str_replace("#", "-",   $file_name);
        $file_name = str_replace(" ", "-",   $file_name);
        $file_name = str_replace("'", "",    $file_name);
        $file_name = str_replace('"', "",    $file_name);
        $file_name = str_replace("__", "-",  $file_name);
        $file_name = str_replace("&", "and", $file_name);
        $file_name = str_replace("?", "",    $file_name);
        return $file_name;
    }
}