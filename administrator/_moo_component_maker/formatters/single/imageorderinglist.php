<?php

class Imageorderinglist_Formatter
{
    public function imageorderinglist($row, $name, $view_arr)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $images = array();

        $carousel_id = isset($row->carousel_id) ? (int) $row->carousel_id : '';

        if ($carousel_id) {
            $query->select('i.image_id, i.filename, i.video_href, i.file_type, r.ordering')
                ->from('#__moo_carousel_image AS i')
                ->join('INNER', '#__moo_carousel_image_ref AS r USING (image_id)')
                ->where('r.carousel_id = ' . $carousel_id)
                ->order('r.ordering ASC');

            $db->setQuery($query);

            $images = $db->loadObjectList();
        }

        if (empty($images)) {
            return 'No images added yet.';
        }

        // start new row
        $html = '</td></tr><tr><td></td><td>';

        foreach ($images as $image) {
            if ('image' === $image->file_type) {
                MooHelper::checkImage($image->filename, true);
                $html .= $image->filename;
            } else {
                $html .= '<a target="_blank" href="' . $image->video_href . '">' . $image->video_href . '</a>';
            }

            $html .= '<input type="hidden" name="image_id[]" value="' . $image->image_id . '" />';
            $html .= '<span class="multifield">Ordering</span>';
            $html .= '<input type="text" name="ordering[]" value="' . $image->ordering . '" />';
            $html .= '<br />';
            $html .= '<div style="clear:both;"></div>';
        }

        return $html;
    }
}