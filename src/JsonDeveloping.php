<?php
namespace FilmTools\Developing;

class JsonDeveloping extends Developing implements \JsonSerializable
{

    public function jsonSerialize()
    {
        return array(
            'zones'      => $this->getZones(),
            'densities'  => $this->getDensities(),
            'N'          => $this->getN(),
            'offset'     => $this->getOffset(),
            'developing' => $this->getDevelopingType()
        );
    }

}
