<?php
namespace FilmTools\Developing;

use FilmTools\Commons\DataLengthMismatchException;
use FilmTools\Commons\FilmToolsInvalidArgumentException;
use FilmTools\Commons\Exposures;
use FilmTools\Commons\ExposuresInterface;
use FilmTools\Commons\ExposuresProviderInterface;
use FilmTools\Commons\Densities;
use FilmTools\Commons\DensitiesInterface;
use FilmTools\Commons\DensitiesProviderInterface;

class Developing implements DevelopingInterface
{

    /**
     * @var int
     */
    public $time;

    /**
     * @var Exposures
     */
    protected $exposures;

    /**
     * @var Densities
     */
    protected $densities;

    /**
     * @var array
     */
    protected $data;


    /**
     * @param ExposuresProviderInterface|float[] $exposures
     * @param DensitiesProviderInterface|float[] $densities
     * @param int     $time
     */
    public function __construct( $exposures, $densities, int $time)
    {
        $this->setExposures( $exposures );
        $this->setDensities( $densities );

        if (count($this->getExposures()) != count( $this->getDensities() ))
            throw new DataLengthMismatchException;

        $this->resetData();

        $this->time = $time;
    }





    /**
     * @inheritDoc
     */
    public function getTime(): int
    {
        return $this->time;
    }


    /**
     * @inheritDoc
     */
    public function count()
    {
        return $this->getExposures()->count();
    }



    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        $data = $this->getData();
        return new \ArrayIterator( $data );
    }


    /**
     * @inheritDoc
     * @return Exposures
     */
    public function getExposures(): ExposuresInterface {
        return $this->exposures;
    }


    /**
     * @param  ExposuresProviderInterface|float[] $exposures [description]
     * @return $this Fluent interface
     */
    protected function setExposures( $exposures )
    {
        if (is_array($exposures))
            $exposures = new Exposures($exposures);

        if (!$exposures instanceOf ExposuresProviderInterface)
            throw new FilmToolsInvalidArgumentException("Array or ExposuresProviderInterface expected");

        $this->exposures = $exposures->getExposures();
        return $this;
    }


    /**
     * @inheritDoc
     * @return Densities
     */
    public function getDensities(): DensitiesInterface {
        return $this->densities;
    }


    /**
     * @param  DensitiesProviderInterface|float[] $exposures [description]
     * @return $this Fluent interface
     */
    protected function setDensities( $densities )
    {
        if (is_array($densities))
            $densities = new Densities($densities);

        if (!$densities instanceOf DensitiesProviderInterface)
            throw new FilmToolsInvalidArgumentException("Array or DensitiesProviderInterface expected");

        $this->densities = $densities->getDensities();
        return $this;
    }


    /**
     * Checks if a given logH exposure exists in this developing run.
     *
     * @inheritDoc
     */
    public function has( $logH )
    {
        $result = $this->getExposures()->search($logH);
        return !is_null($result) and ($result !== false);
    }


    /**
     * Returns the density value for a given logH exposure.
     *
     * @inheritDoc
     * @return float
     */
    public function get( $logH ): float
    {
        if (!$this->has($logH)):
            $msg = sprintf("No data for logH exposure '%s'", $logH);
            throw new ExposureNotFoundException( $msg );
        endif;
        $index = $this->getExposures()->search( $logH );
        return $this->getDensities()->offsetGet( $index );
    }


    /**
     * @inheritDoc
     */
    public function getData() : array
    {
        return $this->data;
    }


    protected function resetData()
    {
        $exposures = array_map(function($e) { return "" . $e; }, $this->getExposures()->getArrayCopy());

        $this->data = array_combine(
            $exposures,
            $this->getDensities()->getArrayCopy()
        );
    }
}
