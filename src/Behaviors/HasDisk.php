<?php

namespace Belt\Core\Behaviors;

use Belt\Core\Helpers\BeltHelper;

/**
 * Class HasDisk
 * @package Belt\Core\Behaviors
 */
trait HasDisk
{
    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    public $disk;

    /**
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public function disk()
    {
        return $this->disk = $this->disk ?: BeltHelper::baseDisk();
    }
}