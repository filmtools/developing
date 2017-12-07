<?php
namespace FilmTools\Developing;

use FilmTools\ExposureSeries\ZonesAwareTrait;
use FilmTools\ExposureSeries\DensitiesAwareTrait;


abstract class DevelopingAbstract implements DevelopingInterface
{

    use ZonesAwareTrait,
        DensitiesAwareTrait;

    /**
     * @var integer
     */
    public $time = null;

    /**
     * @var float
     */
    public $n_deviation = null;

    /**
     * @var float
     */
    public $speed_offset = null;



    /**
     * @inherit
     * @implements DevelopingInterface
     */
    public function getNDeviation()
    {
        return $this->n_deviation;
    }

    /**
     * @inherit
     * @implements DevelopingInterface
     */
    public function getSpeedOffset()
    {
        return $this->speed_offset;
    }



    /**
     * @return string
     */
    public function getDevelopingType()
    {
        $N = $this->getNDeviation();

        if ($N < 0):
            return 'pull';
        elseif ($N > 0):
            return 'push';
        endif;
        return 'normal';
    }


    /**
     * @inherit
     * @implements DevelopingInterface
     */
    public function getTime()
    {
        return $this->time;
    }



    /**
     * @param int $time
     */
    public function setTime( $time )
    {
        $this->time = $time;
        return $this;
    }

}
