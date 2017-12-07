<?php
namespace tests;

use FilmTools\Developing\Developing;
use FilmTools\Developing\DevelopingInterface;
use FilmTools\ExposureSeries\ZonesAwareInterface;
use FilmTools\ExposureSeries\DensitiesAwareInterface;
use PHPUnit\Framework\TestCase;

class DevelopingTest extends TestCase
{

    /**
     * @dataProvider provideValidZonesAndDensities
     */
    public function testSimpleInstantiation( $zones, $densities)
    {
        $calculator = $this->createCalculator( 1 );

        $sut = new Developing( $zones, $densities, $calculator, $calculator);

        $this->assertInstanceOf( DevelopingInterface::class, $sut);
        $this->assertInstanceOf( DensitiesAwareInterface::class, $sut);
        $this->assertInstanceOf( ZonesAwareInterface::class, $sut);
    }


    /**
     * @dataProvider provideValidZonesAndDensities
     */
    public function testNDeviation( $zones, $densities)
    {
        $expected_N = 1.5;
        $calculator = $this->createCalculator($expected_N);

        $sut = new Developing( $zones, $densities, $calculator, $calculator);

        $this->assertEquals($expected_N, $sut->getNDeviation());
    }


    /**
     * @dataProvider provideValidZonesAndDensities
     */
    public function testSpeedOffset( $zones, $densities)
    {
        $expected_offset = 1.5;
        $calculator = $this->createCalculator($expected_offset);

        $sut = new Developing( $zones, $densities, $calculator, $calculator);

        $this->assertEquals($expected_offset, $sut->getSpeedOffset());
    }


    /**
     * @dataProvider provideValidZonesAndDensities
     */
    public function testTimeInterceptors( $zones, $densities)
    {
        $time = 100;
        $calculator = $this->createCalculator(1);
        $sut = new Developing( $zones, $densities, $calculator, $calculator);

        $sut_time = $sut->getTime();
        $this->assertEmpty( $sut_time );
        $this->assertNotEquals( $time, $sut_time );

        $sut->setTime( $time );
        $this->assertEquals( $sut->getTime(), $time );
    }


    /**
     * @dataProvider provideNDeviationAndExpectedType
     */
    public function testDevelopingType( $n, $type)
    {
        $calculator = $this->createCalculator($n);
        $dummy_zd = array(2);
        $sut = new Developing( $dummy_zd, $dummy_zd, $calculator, $calculator);

        $this->assertEquals( $sut->getDevelopingType(), $type );
    }


    public function provideValidZonesAndDensities()
    {
        $a = array(2);

        return array(
            [ $a, $a ],
            [ new \ArrayObject( $a ), new \ArrayObject( $a ) ]
        );
    }

    public function provideNDeviationAndExpectedType()
    {

        return array(
            [ 1, 'push' ],
            [ 0, 'normal' ],
            [ -1, 'pull' ]
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
