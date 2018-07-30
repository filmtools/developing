<?php
namespace FilmTools\Developing;

use FilmTools\Commons\DensitiesInterface;
use FilmTools\Commons\ExposuresInterface;

abstract class DevelopingDecoratorAbstract implements DevelopingInterface
{

    /**
     * @var DevelopingInterface
     */
    protected $developing;


    /**
     * @param DevelopingInterface $developing
     */
    public function __construct (DevelopingInterface $developing)
    {
        $this->developing = $developing;
    }


    /**
     * @inheritDoc
     */
    public function getTime()
    {
        return $this->developing->getTime();
    }


    /**
     * @inheritDoc
     */
    public function getData()
    {
        return $this->developing->getData();
    }


    /**
     * @inheritDoc
     */
    public function count()
    {
        return $this->developing->count();
    }


    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return $this->developing->getIterator();
    }


    /**
     * @inheritDoc
     */
    public function getExposures() : ExposuresInterface
    {
        return $this->developing->getExposures();
    }


    /**
     * @inheritDoc
     */
    public function getDensities() : DensitiesInterface
    {
        return $this->developing->getDensities();
    }


    /**
     * @inheritDoc
     */
    public function has( $logH )
    {
        return $this->developing->has ($logH );
    }


    /**
     * @inheritDoc
     */
    public function get( $logH )
    {
        return $this->developing->get ($logH );
    }
}
