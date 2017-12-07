<?php
namespace FilmTools\Developing;

use FilmTools\ExposureSeries\ZonesAwareInterface;
use FilmTools\ExposureSeries\DensitiesAwareInterface;

interface DevelopingInterface extends ZonesAwareInterface, DensitiesAwareInterface
{
    /**
     * @return float|null
     */
    public function getNDeviation();

    /**
     * @return float|null
     */
    public function getSpeedOffset();

    /**
     * @return string
     */
    public function getDevelopingType();

    /**
     * @return int
     */
    public function getTime();

}
