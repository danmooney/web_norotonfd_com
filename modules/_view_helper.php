<?php

class MooViewHelper
{
    /**
     * @var string
     */
    private $_img_dir;

    /**
     * Repo of last output things
     * @var array
     */
    private $_outputs = array();

    public function __construct($img_dir = '')
    {
        $this->_img_dir = $this->fixDSDuplication($img_dir);
    }

    /**
     * @return string
     */
    public function getImgDir()
    {
        return $this->_img_dir;
    }

    public function output($str, $formatted_output = '')
    {
        $str = trim($str);

        if (null === $str) {
            $this->_outputSuccess(false);
            return '';
        }

        $this->_outputSuccess(true);

        if (empty($str)) {
            return '';
        } elseif (empty($formatted_output)) {
            return $str;
        }

        if (is_callable($formatted_output)) {
            return $formatted_output($str);
        } else {
            return str_replace('{str}', $str, $formatted_output);
        }
    }

    public function imageExists($src, $image_folder = '')
    {
        if (empty($path)) {
            $path = $this->getImgDir();
        } else {
            $path = $this->fixDSDuplication($image_folder . '/');
        }

        return is_file(JPATH_SITE . '/images/' . $path . '/' . $src);
    }

    public function outputImage($src = '', $formatted_output = '', $image_folder = '')
    {
        if (empty($src)) {
            $this->_outputSuccess(false);
            return '';
        }

        if (!$this->imageExists($src, $image_folder . '/')) {
            $this->_outputSuccess(false);
            return '';
        }

        $this->_outputSuccess(true);

        $image_src = sprintf('<img src="%s" />', $this->fixDSDuplication('images/' . $this->getImgDir() . '/' . $src));

        return $this->output($image_src, $formatted_output);
    }

    public function fixDSDuplication($str)
    {
        return str_replace('//', '/', $str);
    }

    /**
     * @param int|string|null $index
     * @return bool
     */
    public function outputWasSuccess($index = null)
    {
        if (null === $index) {
            $index = count($this->_outputs) - 1;
        }

        return (bool) $this->_outputs[$index];
    }

    /**
     * @param string $str
     * @param int $length
     * TODO
     */
    public function truncate($str, $length = 100)
    {
        if (strlen($str) <= $length) {
            return $str;
        }

        return substr($str, 0, strpos(wordwrap($str, $length), "\n")) . '...';
    }

    private function _outputSuccess($success_bool)
    {
        $this->_outputs[] = (bool) $success_bool;
    }
}