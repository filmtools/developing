<?php
namespace FilmTools\Developing;

use FilmTools\Commons\Zones;
use FilmTools\Commons\FStops;
use FilmTools\Commons\Exposures;
use FilmTools\Commons\ExposuresProviderInterface;
use FilmTools\Commons\Densities;
use FilmTools\Commons\DensitiesProviderInterface;

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
     * Expects an array or ArrayAccess with at least elements "time", "densities", and "exposures".
     *
     * If no "exposures" are given, but "zones" numbers are instead, the zone numbers
     * will be converted internally.
     *
     * @param  array|ArrayAccess $developing
     * @return DevelopingInterface
     */
    public function __invoke( $developing )
    {
        if (!is_array($developing) and !$developing instanceOf \ArrayAccess)
            throw new DevelopingInvalidArgumentException("Array or ArrayAccess expected");

        $densities = $this->extractDensities($developing);
        $exposures = $this->extractExposures($developing);
        $time      = $this->extractTime($developing);

        $developing_php_class = $this->developing_php_class;
        return new $developing_php_class( $exposures, $densities, $time );
    }


    protected function extractDensities( $developing ) : DensitiesProviderInterface
    {
        $densities = $developing['densities'] ?? array();

        return new Densities($densities);
    }


    protected function extractExposures( $developing ) : ExposuresProviderInterface
    {
        $fstops    = $developing['fstops']    ?? array();
        $zones     = $developing['zones']     ?? array();
        $exposures = $developing['exposures'] ?? array();


        if (empty($exposures) and !empty($zones)):
            $exposures = new Zones( $zones );
        elseif (empty($exposures) and !empty($fstops)):
            $exposures = new FStops( $fstops );
        else:
            $exposures = new Exposures( $exposures );
        endif;

        return $exposures;
    }


    protected function extractTime( $developing ) : int
    {
        if (!array_key_exists("time", $developing)
        and !array_key_exists("seconds", $developing))
            throw new NoTimeGivenException("The data array must contain either 'time' or 'seconds' element.");

        $time = $developing['seconds'] ?? ($developing['time'] ?? false);

        if (filter_var($time, \FILTER_VALIDATE_INT, ['options' => array( 'min_range' => 0 )]) === false)
            throw new NoTimeGivenException("The developing time must be integer (positive or 0).");

        return $time;
    }
}
