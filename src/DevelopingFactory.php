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
     * @var array
     */
    public $density_fields = array("logD", "density", "densities");

    /**
     * @var array
     */
    public $exposure_fields = array("logH", "exposure", "exposures");


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
    public function __invoke( $developing ) : DevelopingInterface
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
        $density_fields = $this->density_fields;
        $densities = array();

        while (($field = array_shift($density_fields)) and empty($densities)):
            $densities = $developing[ $field ] ?? array();
        endwhile;

        return new Densities($densities);
    }


    protected function extractExposures( $developing ) : ExposuresProviderInterface
    {

        $exposure_fields = $this->exposure_fields;
        $exposures = array();

        while (($field = array_shift($exposure_fields)) and empty($exposures)):
            $exposures = $developing[ $field ] ?? array();
        endwhile;

        if (!empty($exposures)):
            return new Exposures( $exposures );
        endif;

        // So if "exposures" are empty, try to make some
        // using fstops and zone numbers
        $fstops = $developing['fstops']    ?? array();
        $zones  = $developing['zones']     ?? array();

        if (empty($exposures) and !empty($zones)):
            $exposures = new Zones( $zones );
        elseif (empty($exposures) and !empty($fstops)):
            $exposures = new FStops( $fstops );
        else:
            // Found nothing, give up
            $exposures = new Exposures( array() );
        endif;

        return $exposures;
    }


    protected function extractTime( $developing ) : int
    {
        if (!array_key_exists("time", $developing)
        and !array_key_exists("seconds", $developing))
            throw new NoTimeGivenException("The data array must contain either 'time' or 'seconds' element.");

        if (empty($time = $developing['seconds'] ?? false)
        and empty($time = $developing['time'] ?? false)):
            throw new NoTimeGivenException("At least one element in 'seconds' or 'time' expected");
        endif;

        // Remove surfluous time values
        if (is_array($time)):
            $time = array_unique($time);
            $count_times = count($time);
            if ($count_times > 1):
                $msg = sprintf("There must only be one developing time, %s given", $count_times);
                throw new NoTimeGivenException($msg);
            endif;
            $time = array_shift($time);
        endif;

        if (filter_var($time, \FILTER_VALIDATE_INT, ['options' => array( 'min_range' => 0 )]) === false)
            throw new NoTimeGivenException("The developing time must be integer (positive or 0).");

        return $time;
    }
}
