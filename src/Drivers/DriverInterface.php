<?php

namespace Analogue\ORM\Drivers;

interface DriverInterface
{
    /**
     * Return the name of the driver
     * @return string
     */
    public function getName();

    /**
     * Get Analogue DB Adapter
     * @param  string $connection connection name for drivers supporting multiple connections
     * @return \Analogue\ORM\Drivers\DBAdapter
     */
    public function getAdapter($connection = null);
}
