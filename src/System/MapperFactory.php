<?php namespace Analogue\ORM\System;

use Analogue\ORM\EntityMap;
use Illuminate\Contracts\Events\Dispatcher;
use Analogue\ORM\Drivers\Manager as DriverManager;

/**
 * Build a mapper instance from an EntityMap object, doing the 
 * required parsing of relationships. Abstracting to this class
 * will make it easy to later cache the EntityMap for better performances.
 */
class MapperFactory {

    protected $drivers;

    protected $dispatcher;

    public function __construct(DriverManager $drivers, Dispatcher $dispatcher)
    {
        $this->drivers = $drivers;

        $this->dispatcher = $dispatcher;
    }

    /**
     * Return a new Mapper instance
     * 
     * @param  string       $entityClass 
     * @param  string       $entityMap
     * @return Mapper                     
     */
    public function make($entityClass, EntityMap $entityMap)
    {
        $driver = $entityMap->getDriver();
        $connection = $entityMap->getConnection();

        $adapter = $this->drivers->getAdapter($driver, $connection);
        $entityMap->setDateFormat($adapter->getDateFormat());

        $mapper = new Mapper($entityMap, $adapter, $this->dispatcher);

        // Fire Initializing Event
        $mapper->fireEvent('initializing', $mapper);
        
        $mapInitializer = new MapInitializer($entityMap);
        
        $mapInitializer->init();

        // Fire Initialized Event
        $mapper->fireEvent('initialized', $mapper);

        return $mapper;
    }

}