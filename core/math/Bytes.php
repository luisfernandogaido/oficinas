<?php
namespace math;

class Bytes
{
    private int $bytes;

    /**
     * Bytes constructor.
     * @param $bytes
     */
    public function __construct($bytes)
    {
        $this->bytes = $bytes;
    }

    /**
     * @return string
     */
    public function formata(): string
    {
        if ($this->bytes < 1024) {
            return $this->bytes . 'B';
        }
        if ($this->bytes < 1024 * 1024) {
            return round($this->bytes / 1024, 1) . 'KB';
        }
        if ($this->bytes < 1024 * 1024 * 1024) {
            return round($this->bytes / 1024 / 1024, 1) . 'MB';
        }
        if ($this->bytes < 1024 * 1024 * 1024 * 1024) {
            return round($this->bytes / 1024 / 1024 / 1024, 1) . 'GB';
        }
        if ($this->bytes < 1024 * 1024 * 1024 * 1024 * 2014) {
            return round($this->bytes / 1024 / 1024 / 1024 / 1024, 1) . 'TB';
        }
        return 'error';
    }
}