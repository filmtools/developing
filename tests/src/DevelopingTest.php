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

class DevelopingTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @dataProvider provideValidCtorArguments
     */
    public function testValidCtorArguments( $exposures, $densities, $time)
    {
        $sut = new Developing( $exposures, $densities, $time );
        $this->assertInstanceOf( DevelopingInterface::class, $sut );

        $this->assertEquals( $time, $sut->getTime() );
        $this->assertInstanceOf( ExposuresInterface::class, $sut->getExposures() );
        $this->assertInstanceOf( DensitiesInterface::class, $sut->getDensities() );

    }


    public function testContainerInterface()
    {
        $data = array( 0.1 => 1, 0.2 => 2, 0.3 => 3);

        $exposures_provider = $this->prophesize( ExposuresProviderInterface::class );
        $exposures_provider->getExposures()->willReturn( new Exposures( array_keys($data) ) );
        $exposures_provider_mock = $exposures_provider->reveal();

        $densities_provider = $this->prophesize( DensitiesProviderInterface::class );
        $densities_provider->getDensities()->willReturn( new Densities( $data ));
        $densities_provider_mock = $densities_provider->reveal();

        $sut = new Developing( $exposures_provider_mock, $densities_provider_mock, 99 );

        $this->assertEquals( $data, $sut->getData());

        $this->assertInstanceOf( ContainerInterface::class, $sut );
        foreach($data as $logH => $logD):
            $this->assertTrue( $sut->has( $logH ));
            $this->assertEquals( $sut->get( $logH ), $logD);
        endforeach;

        $this->expectException( NotFoundExceptionInterface::class );
        $sut->get( 42 );
    }


    public function testDataIntegrity()
    {
        $data = array( 0.1 => 1, 0.2 => 2, 0.3 => 3, 0.4 => 4);

        $exposures_provider = $this->prophesize( ExposuresProviderInterface::class );
        $exposures_provider->getExposures()->willReturn( new Exposures( array_keys($data) ) );
        $exposures_provider_mock = $exposures_provider->reveal();

        $densities_provider = $this->prophesize( DensitiesProviderInterface::class );
        $densities_provider->getDensities()->willReturn( new Densities( $data ));
        $densities_provider_mock = $densities_provider->reveal();

        $sut = new Developing( $exposures_provider_mock, $densities_provider_mock, 99 );


        $this->assertEquals( $data, $sut->getData());
        $this->assertEquals( array_values($data), $sut->getDensities()->getArrayCopy());
        $this->assertEquals( array_keys($data), $sut->getExposures()->getArrayCopy());

    }




    /**
     * @dataProvider provideInvalidCtorArguments
     */
    public function testInvalidCtorArguments( $exposures, $densities, $time)
    {
        $this->expectException(DataLengthMismatchException::class);
        new Developing( $exposures, $densities, $time );
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



    public function provideInvalidCtorArguments()
    {
        $data = array( 1, 2, 3);
        $exposures_provider = $this->prophesize( ExposuresProviderInterface::class );
        $exposures_provider->getExposures()->willReturn( new Exposures( $data ) );
        $exposures_provider_mock = $exposures_provider->reveal();

        $densities_provider = $this->prophesize( DensitiesProviderInterface::class );
        $densities_provider->getDensities()->willReturn( new Densities( array() ));
        $densities_provider_mock = $densities_provider->reveal();

        $time = 99;

        return array(
            [ $exposures_provider_mock, $densities_provider_mock, $time  ],
            [ $data, array(), $time  ]
        );
    }
}
