<?php

class UcFirst_Formatter
{
    public function ucfirst($row, $name, $view_arr)
    {
        return ucfirst($row->$name);
    }
}