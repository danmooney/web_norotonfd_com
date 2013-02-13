<?php

class Boolean_Formatter
{
    public function boolean($row, $name, $table_header)
    {
        return (intval($row->$name) === 1)
            ? 'YES'
            : 'NO';
    }
}