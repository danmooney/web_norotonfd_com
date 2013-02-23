<?php

class Nl2br_Formatter
{
    public function nl2br($row, $name, $view_arr)
    {
        return nl2br($row->$name);
    }
}