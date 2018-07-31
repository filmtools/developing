<?php
namespace tests;

use FilmTools\Developing\Developing;
use FilmTools\Developing\DevelopingInterface;
use FilmTools\Commons\ExposuresProviderInterface;
use FilmTools\Commons\ExposuresInterface;
use FilmTools\Commons\Exposures;
use FilmTools\Commons\DensitiesProviderInterface;
use FilmTools\Commons\DensitiesInterface;
use FilmTools\Commons\Densities;
use FilmTools\Commons\DataLengthMismatchException;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;



class DevelopingDecoratorAbstractTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @dataProvider provideValidCtorArguments
     */
    public function testValidCtorArguments( $exposures, $densities, $time)
    {
        $developing = new Developing( $exposures, $densities, $time );
        $sut = new DevelopingDecorator( $developing );

        $this->assertInstanceOf( DevelopingInterface::class, $sut );

        $this->assertEquals( $sut->getTime(),      $developing->getTime());
        $this->assertEquals( $sut->getData(),      $developing->getData());
        $this->assertEquals( $sut->count(),        $developing->count());
        $this->assertEquals( $sut->getIterator(),  $developing->getIterator());
        $this->assertEquals( $sut->getExposures(), $developing->getExposures());
        $this->assertEquals( $sut->getDensities(), $developing->getDensities());

    }



    public function provideValidCtorArguments()
    {
        $data = array( 1, 2, 3);
        $exposures_provider = $this->prophesize( ExposuresProviderInterface::class );
        $exposures_provider->getExposures()->willReturn( new Exposures( $data ) );
        $exposures_provider_mock = $exposures_provider->reveal();

        $densities_provider = $this->prophesize( DensitiesProviderInterface::class );
        $densities_provider->getDensities()->willReturn( new Densities( $data ));
        $densities_provider_mock = $densities_provider->reveal();

        $time = 99;

        return array(
            [ $exposures_provider_mock, $densities_provider_mock, $time  ],
            [ $data, $data, $time  ]
        );
    }


}
