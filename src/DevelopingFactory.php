<?php
namespace FilmTools\Developing;


class DevelopingFactory
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
     * @param callable $n_calculator      Callable accepting zones and densities array
     * @param callable $speed_calculator  Callable accepting zones and densities array
     */
    public function __construct( callable $n_calculator, callable $speed_calculator )
    {
        $this->n_calculator = $n_calculator;
        $this->speed_calculator = $speed_calculator;
    }


    /**
     * @param  int                 $time
     * @param  float[]|Traversable $zones      Optional
     * @param  float[]|Traversable $densities  Optional
     *
     * @return Developing
     */
    public function __invoke( $time, $zones = array(), $densities = array() )
    {
        $developing = new Developing( $zones, $densities, $this->n_calculator, $this->speed_calculator );
        $developing->setTime( $time );
        return $developing;
    }
}
