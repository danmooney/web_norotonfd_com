<?php

defined('_JEXEC') or die('Restricted Access');

?>

<div id="carousel-controls"></div>
<ul class="carousel">
    <?php
        foreach ($images as $image):
            echo $helper->outputImage(
                 $image->filename, '<li>{str}'
               . $helper->output($image->text, '<div class="overlay">{str}</div>')
               . '</li>'
            );
        endforeach
?>
</ul>
