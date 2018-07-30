<?php
namespace FilmTools\Developing;

use FilmTools\Commons\DensitiesProviderInterface;
use FilmTools\Commons\ExposuresProviderInterface;
use Psr\Container\ContainerInterface;

interface DevelopingInterface extends DensitiesProviderInterface, ExposuresProviderInterface, ContainerInterface, \Countable, \IteratorAggregate
{

    /**
     * Returns the developing time.
     *
     * @return int
     */
    public function getTime() : int;


    /**
     * Returns an array with exposure and density values.
     *
     * @return array
     */
    public function getData() : array;
}
