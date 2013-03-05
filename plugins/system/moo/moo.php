<?php

defined('_JEXEC') or die;

class plgSystemMoo extends JPlugin
{
    public function __construct(&$subject, $config)
    {
//        if ($_SERVER['REMOTE_ADDR'] !== '108.204.9.143') {
//            die('Site is currently under construction.');
//        }
        parent::__construct($subject, $config);
    }

    public function onAfterInitialise()
    {
        $is_ajax_request = (
            'POST' === $_SERVER['REQUEST_METHOD'] &&
            (
                isset($_POST['ajax'], $_POST['cid']) &&
                intval($_POST['ajax']) === 1 &&
                is_numeric($_POST['cid'])
            ) ||
            (
                isset($_POST['ajax']) &&
                isset($_POST['username']) &&
                isset($_POST['password'])
            )
        );

        if (!$is_ajax_request) {
            return;
        }

        if (isset($_POST['cid'])) {
            $this->_outputGalleryImageList();
        } elseif (isset($_POST['username']) &&
            isset($_POST['password'])
        ) {
            $app = JFactory::getApplication();
            $input = $app->input;

            $username = $input->get('username', '', 'string');
            $password = $input->get('password', '', 'string');
            $remember = (bool) $input->get('remember', 0, 'int');

            $credentials = array(
                'username' => $username,
                'password' => $password
            );

            $options = array(
                'remember' => $remember
            );

            if ($app->login($credentials, $options)) { // success
                echo 'success';
            } else { // failure
                echo 'failure';
            }

            exit(0);
        }
    }

    private function _outputGalleryImageList()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('filename')
            ->from('#__moo_gallery_image')
            ->where('gallery_id = ' . (int) $_POST['cid'])
            ->order('ordering ASC');

        $db->setQuery($query);
        $results = $db->loadColumn();

        echo json_encode($results);
        exit(0);
    }
}