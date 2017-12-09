<?php
namespace tests;

use FilmTools\Developing\JsonDeveloping;
use FilmTools\Developing\DevelopingInterface;
use FilmTools\ExposureSeries\ZonesAwareInterface;
use FilmTools\ExposureSeries\DensitiesAwareInterface;
use PHPUnit\Framework\TestCase;

class JsonDevelopingTest extends TestCase
{

    /**
     * @dataProvider provideValidZonesAndDensities
     */
    public function testSimpleInstantiation( $zones, $densities)
    {
        $calculator = $this->createCalculator( 1 );

        $sut = new JsonDeveloping( $zones, $densities, $calculator, $calculator);

        $this->assertInstanceOf( \JsonSerializable::class, $sut);
    }


    /**
     * @dataProvider provideValidZonesAndDensities
     */
    public function testJsonSerialization( $zones, $densities)
    {
        $expected_N = 1.5;
        $calculator = $this->createCalculator($expected_N);

        $sut = new JsonDeveloping( $zones, $densities, $calculator, $calculator);

        $data = json_decode(json_encode( $sut ));

        $this->assertObjectHasAttribute('N', $data);
        $this->assertObjectHasAttribute('developing', $data);
        $this->assertObjectHasAttribute('zones', $data);
        $this->assertObjectHasAttribute('densities', $data);
        $this->assertObjectHasAttribute('offset', $data);
        $this->assertObjectHasAttribute('gamma', $data);
        $this->assertObjectHasAttribute('beta', $data);
    }



    public function provideValidZonesAndDensities()
    {
        $a = array(2);

        return array(
            [ $a, $a ],
            [ new \ArrayObject( $a ), new \ArrayObject( $a ) ]
        );
    }


    /**
     * @return Callable
     */
    protected function createCalculator( $result )
    {
        return function($zones, $densities) use ($result) {
            return $result;
        };
    }


}
