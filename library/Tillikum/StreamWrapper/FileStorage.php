<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\StreamWrapper;

use Traversable;
use Zend\Stdlib\ArrayUtils;

class FileStorage
{
    protected static $defaultOptions = array();

    protected $fp;
    protected $hash;
    protected $options;

    public static function getDefaultOptions()
    {
        return static::$defaultOptions;
    }

    public static function setDefaultOptions($defaultOptions)
    {
        if ($defaultOptions instanceof Traversable) {
            $defaultOptions = ArrayUtils::iteratorToArray($defaultOptions);
        } elseif (!is_array($defaultOptions)) {
            throw new Exception\InvalidArgumentException(
                '$defaultOptions must be an array or a Traversable.'
            );
        }

        static::$defaultOptions = $defaultOptions;
    }

    public function init()
    {
        $this->options = array_replace_recursive(
            array(
                'directory_mask' => 0755,
                'root' => sys_get_temp_dir(),
            ),
            static::getDefaultOptions()
        );
    }

    public function stream_cast($as)
    {
        return $this->fp;
    }

    public function stream_close()
    {
        fclose($this->fp);
    }

    public function stream_eof()
    {
        return feof($this->fp);
    }

    public function stream_flush()
    {
        return fflush($this->fp);
    }

    public function stream_open($path, $mode, $options, &$openedPath)
    {
        $this->init();

        $url = parse_url($path);

        $this->hash = $url['host'];

        $path = $this->getPathFromHash($this->hash);

        if (!is_dir($path)) {
            $mkdirRet = mkdir($path, $this->options['directory_mask'], true);

            if (!$mkdirRet) {
                return false;
            }
        }

        $openedPath = "{$path}/{$this->hash}";

        $this->fp = fopen($openedPath, $mode);

        if (!$this->fp) {
            return false;
        }

        return true;
    }

    public function stream_read($length)
    {
        return fread($this->fp, $length);
    }

    public function stream_seek($offset, $whence)
    {
        return fseek($this->fp, $offset, $whence);
    }

    public function stream_stat()
    {
        return fstat($this->fp);
    }

    public function stream_tell()
    {
        return ftell($this->fp);
    }

    public function stream_write($data)
    {
        return fwrite($this->fp, $data);
    }

    public function unlink($path)
    {
        $this->init();

        $url = parse_url($path);

        $hash = $url['host'];

        $basePath = $this->getPathFromHash($hash);

        return unlink("{$basePath}/{$hash}");
    }

    protected function getPathFromHash($hash)
    {
        $firstLevel = substr($hash, 0, 1);
        $secondLevel = substr($hash, 1, 2);

        return "{$this->options['root']}/{$firstLevel}/{$secondLevel}";
    }
}
