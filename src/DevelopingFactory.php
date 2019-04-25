<?php
namespace FilmTools\Developing;

use FilmTools\Commons\Zones;

class DevelopingFactory
{

    /**
     * PHP class name (FQDN) of the DevelopingInterface instance this factory produces.
     *
     * @var string
     */
    public $developing_php_class;


    /**
     * @param string|null $developing_php_class DevelopingInterface instance FQDN
     *
     * @throws InvalidArgumentException If FQDN does not implement DevelopingInterface
     */
    public function __construct( string $developing_php_class = null )
    {
        $this->developing_php_class = $developing_php_class ?: Developing::class;

        if (!is_subclass_of($this->developing_php_class, DevelopingInterface::class )):
            $msg = sprintf("Class name must implement '%s'.", DevelopingInterface::class);
            throw new DevelopingInvalidArgumentException( $msg );
        endif;
    }


    /**
     * The factory method.
     *
     * Expects an array with at least elements "time", "densities", and "exposures".
     *
     * If no "exposures" are given, but "zones" numbers are instead, the zone numbers
     * will be converted internally.
     *
     * @param  array $developing
     * @return DevelopingInterface
     */
    public function __invoke( $developing )
    {
        $densities = $developing['densities'] ?? array();
        $zones     = $developing['zones']     ?? array();
        $exposures = $developing['exposures'] ?? array();

        if (!array_key_exists("time", $developing))
            throw new NoTimeGivenException("The data array must contain a 'time' element.");

        $time = $developing['time'] ?? false;
        if (empty($time))
            throw new NoTimeGivenException("The developing time must not be empty.");

        if (empty($exposures) and !empty($zones)):
            $exposures = new Zones( $zones );
        endif;

        $developing_php_class = $this->developing_php_class;
        return new $developing_php_class( $exposures, $densities, $time );
    }
}
