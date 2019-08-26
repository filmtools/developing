<?php
namespace tests;

use FilmTools\Developing\DevelopingFactory;
use FilmTools\Developing\DevelopingInterface;
use FilmTools\Developing\NoTimeGivenException;
use FilmTools\Developing\DevelopingExceptionInterface;
use FilmTools\Developing\DevelopingInvalidArgumentException;

use FilmTools\Commons\ExposuresProviderInterface;
use FilmTools\Commons\ExposuresInterface;
use FilmTools\Commons\Exposures;
use FilmTools\Commons\DensitiesProviderInterface;
use FilmTools\Commons\DensitiesInterface;
use FilmTools\Commons\Densities;
use FilmTools\Commons\DataLengthMismatchException;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class DevelopingFactoryTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @dataProvider provideValidCtorArguments
     */
    public function testValidCtorArguments( $data )
    {
        $sut = new DevelopingFactory(  );
        $result = $sut( $data );
        $this->assertInstanceOf( DevelopingInterface::class, $result );

    }

    public function provideValidCtorArguments()
    {
        return array(
            [[
                'time' => 99,
                'densities' => array(),
                'exposures' => array(),
                'zones' => array(),
            ]],
            [[
                'time' => 99,
                'densities' => array( 1, 2, 3),
                'zones' => array( 1, 2, 3),
            ]],
            [[
                'time' => ['99', 99, '99'],
                'densities' => array( 1, 2, 3),
                'fstops' => array( -5, 0, 1),
            ]],
            [[
                'time' => 0,
                'densities' => array( 1, 2, 3),
                'zones' => array( 1, 2, 3),
            ]]
        );
    }


    public function testInvalidCtorArguments( )
    {
        $this->expectException( DevelopingExceptionInterface::class );
        $this->expectException( DevelopingInvalidArgumentException::class );
        $sut = new DevelopingFactory( "foobar" );
    }



    /**
     * @dataProvider provideInvalidFactoryArguments
     */
    public function testInvalidFactoryArguments( $data, $exception_class = null )
    {
        $sut = new DevelopingFactory;
        $this->expectException( DevelopingExceptionInterface::class );

        if ($exception_class)
            $this->expectException( $exception_class );

        $sut( $data );
    }


    public function provideInvalidFactoryArguments()
    {
        return array(
            [ "some_string", DevelopingInvalidArgumentException::class ],
            [[
                'densities' => array(),
                'exposures' => array(),
                'zones' => array(),
            ],  NoTimeGivenException::class],
            [[
                'time' => '',
                'densities' => array( 1, 2, 3),
                'zones' => array( 1, 2, 3),
            ]],
            [[
                'time' => null,
                'densities' => array( 1, 2, 3),
                'zones' => array( 1, 2, 3),
            ]],
            [[
                'time' => false,
                'densities' => array( 1, 2, 3),
                'zones' => array( 1, 2, 3),
            ]],
            [[
                'time' => -100,
                'densities' => array( 1, 2, 3),
                'zones' => array( 1, 2, 3),
            ]],
            [[
                'time' => [100, 101],
                'densities' => array( 1, 2, 3),
                'zones' => array( 1, 2, 3),
            ]]
        );
    }






}
