<?php

MooHelper::restrictAccess();

class MooController extends JController
{
    public $cid;
    public $files_to_upload;

    public function __construct()
    {
        $this->cid = JRequest::getVar('cid', array());
        $this->primary_key = MooHelper::getPrimaryKey();
        parent::__construct();
    }
    
    public function add()
    {
        JRequest::setVar('view', 'Single');    
        $this->display();
    }
    
    public function edit()
    {
        JRequest::setVar('view', 'Single');
        $this->display();
    }

    public function save()
    {
        $id = JRequest::getInt('id');
        $primary_key = $this->primary_key;
        $row = new MooTable();
        unset($row->primary_key);
        if (!$row->bind(JRequest::get('post'))) {
            JError::raiseError(500, $row->getError());
        }
        
        if ($id) {
            $row->$primary_key = $id;
        } elseif (empty($row->ordering) &&
            !is_null(JRequest::getVar('ordering'))
        ) {
            $row->ordering = $row->getNextOrder();
        }

        /**
         * @var $model MooModelSingle
         */
        $model = MooHelper::loadModel('single');

        $model->preHook($row);

        $model->addHTMLToAllowedProperties($row);
        $model->removeEmptyProperties($row);
        $null_fields_bool = $model->convertNullStrToNull($row);

        if ($this->checkForFileUpload()) {
            foreach ($this->files_to_upload as $file_to_upload) {
                $model->file_to_upload = 'new_' . $file_to_upload;
                if (!$model->uploadFile($row)) {
                    JError::raiseError(500, @$model->upload_error);
                }
                
                $model->deleteFile(JRequest::getVar($file_to_upload));
                
                $key       = $file_to_upload;
                $row->$key = $model->file_name;
            }
        }
        
        $model->trimProperties($row);
        $model->convertDateColumnsToMySQLFormat($row);
        $model->postHook($row);

        if (!$row->store((bool) $null_fields_bool)) {
            JError::raiseError(500, $row->getError());
        }

        $model->afterSave($row);

        $this->cid = $model->id = $row->$primary_key;
        $model->saveTableRefs();

        $this->setRedirect('saved');
    }
    
    public function remove()
    {
        $row = new MooTable();
        foreach ($this->cid as $id) {
            $id = (int) $id;
            $row->load($id);
            if (!$row->delete($id)) {
                JError::raiseError(500, $row->getError());
            } 
        }
        $this->setRedirect('deleted');
    }
    
    public function order()
    {
        $id = (int) $this->cid[0];
        $row = new MooTable();
        $row->load($id);
        unset($row->primary_key);
        
        if ($this->getTask() == 'orderdown') {
            $dir = 1;
        } else {
            $dir = -1;
        }
        $row->move($dir);
        $this->setRedirect('reordered');
    }
    
    public function saveOrder() 
    {
        $order = JRequest::getVar('order', array(), 'post', 'array');
        JArrayHelper::toInteger($this->cid);
        JArrayHelper::toInteger($order);

        $row = new MooTable();
        unset($row->primary_key);
        $groupings = array();

        // update ordering values
        for ($i = 0; $i < count($this->cid); $i += 1) {
            $row->load((int) $this->cid[$i]);
            // track categories
            if ($row->ordering != $order[$i]) {
                $row->ordering = $order[$i];
                
                if (!$row->store()) {
                    JError::raiseError(500, $row->getError());
                    return false;
                }
            }
        }

        $this->setRedirect('order saved');
    }
   
    public function publish()
    {
        $arr_current_page = MooConfig::get('arr_current_page');
        $view = $arr_current_page['view']['all'];
        if (isset($view['published']) && isset($view['published']['table'])) {
            $row = new MooTable($view['published']['table']);
        } else {
            $row = new MooTable();
        }

        $task = MooConfig::get('task');
        
        if ($task == 'unpublish') {
            $publish = 0; 
        } else {
            $publish = 1;
        }


        
        if (!$row->publish($this->cid, $publish)){
            JError::raiseError(500, $row->getError());
        } 
        
        $this->setRedirect($this->task . 'ed');
    }
    
    public function initialize()
    {
        if (!JRequest::getVar('view')) {
            JRequest::setVar('view', 'All');
        }
        
        $this->registerTask('apply', 'save');
        $this->registerTask('unpublish', 'publish');
        $this->registerTask('orderup', 'order');
        $this->registerTask('orderdown', 'order');
        $this->registerTask('saveOrder', 'saveOrder');
        $this->execute(MooConfig::get('task'));
        $this->redirect();
    }
    
    public function setRedirect($appended_msg)
    {
        $task         = MooConfig::get('task');
        $current_page = MooConfig::get('current_page');
        $arr_current_page = MooConfig::get('arr_current_page');

        if ($task == 'apply') {
            if (isset($arr_current_page['saved_message_action'])) {
                $appended_msg = $arr_current_page['saved_message_action'];
            }
            $apply_url_params = '&task=edit&cid[]=' . $this->cid;
        } else {
            $apply_url_params = '';
        }
        
        $redirect_url = 'index.php?option=' . MooConfig::get('option') . '&type=' . MooConfig::get('type') . $apply_url_params;

        if (count($this->cid) > 1) {
            $redirect_msg =  MooHelper::makeReadable(
                $current_page
            ) . ' ' . $appended_msg . '.';
        } else {
            $redirect_msg =  MooHelper::makeReadable(
                MooHelper::makeSingular($current_page)
            ) . ' ' . $appended_msg . '.';
        }
        
        parent::setRedirect($redirect_url, $redirect_msg);
    }

    /**
     * Override display and load MooViewAll/MooViewSingle
     */
    public function display($cachable = false, $urlparams = false)
    {
        $viewName = JRequest::getCmd('view');

        $arr_current_page = MooConfig::get('arr_current_page');
        if (!isset($arr_current_page['view']['all'])) {
            $viewName = 'Single';
        }

        switch ($viewName) {
            case 'All':
                require_once(MOO_COMPONENT_PATH . DS . 'views' . DS . 'all' . DS . 'view.html.php');
                require_once(MOO_COMPONENT_PATH . DS . 'models' . DS . 'all.php');
                $view = new MooViewAll();
                $model = new MooModelAll();
                $view->setModel($model);
                break;
            case 'Single':
                require_once(MOO_COMPONENT_PATH . DS . 'views' . DS . 'single' . DS . 'view.html.php');
                $view = new MooViewSingle();
                break;
        }

        // Get/Create the model
        if ($model = $this->getModel($viewName)) {
            // Push the model into the view (as default)
            $view->setModel($model, true);
        }

        $view->display();
    }
    
    private function checkForFileUpload()
    {
        $arr_current_page = MooConfig::get('arr_current_page');
        $this->files_to_upload = array();
        foreach ($arr_current_page['view']['single'] as $key => $field) {
            $new_key = 'new_' . $key;
            if (isset($_FILES[$new_key]['tmp_name'])
            && !empty($_FILES[$new_key]['tmp_name'])
            ) {
                $this->files_to_upload[] = $key;
            }
        }
        if (count($this->files_to_upload) > 0) {
            return true;
        } else {
            return false;
        } 
    }
}

$controller = new MooController;
$controller->initialize();
