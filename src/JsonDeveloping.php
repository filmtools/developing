<?php
namespace FilmTools\Developing;

class JsonDeveloping extends Developing implements \JsonSerializable
{

    public function jsonSerialize()
    {
        return array(
            'zones'      => $this->getZones(),
            'densities'  => $this->getDensities(),
            'N'          => $this->getNDeviation(),
            'offset'     => $this->getSpeedOffset(),
            'developing' => $this->getDevelopingType(),
            'gamma'      => $this->getGammaContrast(),
            'beta'       => $this->getBetaContrast()
        );
    }

}
