<?php
namespace FilmTools\Developing;


class Developing extends DevelopingAbstract implements DevelopingInterface
{

    /**
     * @var Callable
     */
    public $n_calculator;

    /**
     * @var Callable
     */
    public $speed_calculator;



    /**
     * @param float[]|Traversable $zones
     * @param float[]|Traversable $densities
     * @param callable            $n_calculator      Callable accepting zones and densities array
     * @param callable            $speed_calculator  Callable accepting zones and densities array
     */
    public function __construct( $zones, $densities, callable $n_calculator, callable $speed_calculator )
    {
        $this->setZones( $zones );
        $this->setDensities( $densities );
        $this->n_calculator     = $n_calculator;
        $this->speed_calculator = $speed_calculator;
    }


    /**
     * @inherit
     * @implements DevelopingInterface
     */
    public function getNDeviation()
    {
        if (is_null($this->n_deviation)):
            $n_calculator = $this->n_calculator;
            $this->n_deviation = $n_calculator( $this->getZones(), $this->getDensities() );
        endif;

        return $this->n_deviation;
    }

    /**
     * @inherit
     * @implements DevelopingInterface
     */
    public function getSpeedOffset()
    {
        if (is_null($this->speed_offset)):
            $speed_calculator = $this->speed_calculator;
            $this->speed_offset = $speed_calculator( $this->getZones(), $this->getDensities() );
        endif;

        return $this->speed_offset;
    }




}
