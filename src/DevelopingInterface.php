<?php
namespace FilmTools\Developing;

use FilmTools\ExposureSeries\ZonesAwareInterface;
use FilmTools\ExposureSeries\DensitiesAwareInterface;
use FilmTools\Films\FilmAwareInterface;

interface DevelopingInterface extends ZonesAwareInterface, DensitiesAwareInterface, FilmAwareInterface
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
     * @return float|null
     */
    public function getGammaContrast();

    /**
     * @return float|null
     */
    public function getBetaContrast();

    /**
     * @return string
     */
    public function getDevelopingType();

    /**
     * @return int
     */
    public function getTime();

}
