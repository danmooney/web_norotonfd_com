<?php

defined('_JEXEC') or die;

jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldCarousel extends JFormFieldList
{
    protected $type = 'Carousel';

    public function getOptions()
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('carousel_id, title')
            ->from('#__moo_carousel')
//            ->order('title = "Home" DESC, title ASC')
            ->query();

        $db->setQuery($query);

        $results = $db->loadObjectList();

        $options = array();

        foreach ($results as $result) {
            $options[$result->carousel_id] = $result->title;
        }

        return $options;
    }
}