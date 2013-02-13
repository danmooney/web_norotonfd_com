<?php
require_once('WideImage.php');

$url = $_GET['imgfile'];
$width = $_GET['width'];
$height = $_GET['height'];
$tmpPath = '../../cache/';
$fileName = $width.'px_'.$height.'_px_'.basename($url);

if(file_exists($tmpPath.$fileName)):

	$img = WideImage::load($tmpPath.$fileName); 
	$img->output('jpg', 80);

else:

	$img = WideImage::load($url); 
	$img->resize($width, $height, 'outside')->crop('center', 'center', $width, $height)->saveToFile($tmpPath.$fileName, 80);
	
	$img = WideImage::load($tmpPath.$fileName); 
	$img->output('jpg', 80);
	

endif;
?>
