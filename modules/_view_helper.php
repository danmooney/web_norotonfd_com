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

    /**
     * List of US States
     * @var array
     */
    public $states = array(
        'AL',
        'AK',
        'AZ',
        'AR',
        'CA',
        'CO',
        'CT',
        'DE',
        'DC',
        'FL',
        'GA',
        'HI',
        'ID',
        'IL',
        'IN',
        'IA',
        'KS',
        'KY',
        'LA',
        'ME',
        'MD',
        'MA',
        'MI',
        'MN',
        'MS',
        'MO',
        'MT',
        'NE',
        'NV',
        'NH',
        'NJ',
        'NM',
        'NY',
        'NC',
        'ND',
        'OH',
        'OK',
        'OR',
        'PA',
        'RI',
        'SC',
        'SD',
        'TN',
        'TX',
        'UT',
        'VT',
        'VA',
        'WA',
        'WV',
        'WI',
        'WY'
    );

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

    public function urlify($value, $https = false)
    {
        if (!preg_match("~^(?:f|ht)tps?://~i", $value)) {
            if (false === $https) {
                $value = "http://" . $value;
            } else {
                $value = "https://" . $value;
            }
        }

        return $value;
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
        if (empty($image_folder) || $image_folder === '/') {
            $path = $this->getImgDir();
        } else {
            $path = $this->fixDSDuplication($image_folder . '/');
        }

        return $this->fixDSDuplication(is_file(JPATH_SITE . '/images/' . $path . '/' . $src));
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

        if (empty($image_folder)) {
            $image_src_path = $this->fixDSDuplication('images/' . $this->getImgDir() . '/' . $src);
        } else {
            $image_src_path = $this->fixDSDuplication('images/' . $image_folder . '/' . $src);
        }


        $image_src = sprintf('<img src="%s" />', $image_src_path);

        return $this->output($image_src, $formatted_output);
    }

    public function fixDSDuplication($str)
    {
        return preg_replace('/(\/)+/', '/', $str);
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