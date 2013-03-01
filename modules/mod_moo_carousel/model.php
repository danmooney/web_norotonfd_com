<?php

class ModMooCarousel
{
    private $_table = '#__moo_carousel_image';

    /**
     * @var array
     */
    private $_images;

    /**
     * @param string $table
     */
    public function setTable($table)
    {
        $this->_table = $table;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->_table;
    }


    public function getDelay()
    {
        return $this->_params->get('delay', 4000);
    }

    /**
     * @var JRegistry
     */
    private $_params;

    public function __construct(JRegistry $params)
    {
        $this->_params = $params;
    }

    public function getImages()
    {
        if (!isset($this->_images)) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query
                ->select('*')
                ->from($this->getTable())
                ->where('published = 1')
                ->order('ordering ASC');

            $db->setQuery($query);
            $this->_images = $db->loadObjectList();
        }

        return $this->_images;
    }
}