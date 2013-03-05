<?php
/**
 * @var $this \Moo\Gallery\ViewSingle
 */
$row    = $this->row;
$helper = $this->helper;

$number_per_row = 4;

defined('_JEXEC') or die('Restricted Access.');

echo $helper->output($row->title, '<h1 class="bold">{str}</h1>');

if (empty($row->images)):
    echo 'There are currently no images for this gallery.';
else:

    echo $helper->output($row->text);

    $i = 0;

?>
    <div id="gallery-container">
<?php
    foreach ($row->images as $image):
        $thumbnail_image_exists = is_file(JPATH_SITE . DS . 'images' . DS . $helper->getImgDir() . DS . 'thumbs' . DS . $image->filename);

        if (!$thumbnail_image_exists) {
            continue;
        }

        if ($i % $number_per_row === 0) {
            $idx_str = 'first';
        } else if ($i % $number_per_row === ($number_per_row - 1)) {
            $idx_str = 'last';
        } else {
            $idx_str = '';
        }

        echo $helper->outputImage(
                $image->filename,
                sprintf('<div class="image-container %s"><a rel="gallery" href="/images/gallery/%s">{str}</a></div>', $idx_str, $image->filename),
                'gallery/thumbs'
        );

        if ('last' === $idx_str) {
            echo '<div class="clr"></div>';
        }

        $i += 1;
    endforeach; ?>
        <div class="clr"></div>
     </div>

<?php
endif;

