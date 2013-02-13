<?php

class Imagelist_Formatter
{
    public function imagelist($row, $name, $table_header)
    {
        $html = '';
        // comma separated filenames with ids prepended to each, followed by colon
        // i.e. 2:person1.jpg
        $filenames = $row->$name;

        if (empty($filenames)) {
            return 'NONE';
        }

        $filenames_arr = explode(',', $filenames);

        foreach ($filenames_arr as $filename) {
            $id_filename_arr = explode(':', $filename);

            $id = $id_filename_arr[0];

            $filename = $id_filename_arr[1];


            $link = 'index.php?option=' . MooConfig::get('option') . '&type=images&task=edit&cid[]=' . $id;

            MooHelper::checkImage($filename, true);
            if (null !== $filename) {
                $html .= '<a href="' . $link . '">';
                $html .= $filename;
                $html .= '</a>';
            }
        }
        return $html;
    }
}