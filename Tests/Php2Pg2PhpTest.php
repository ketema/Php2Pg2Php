<?php

namespace Php2Pg2Php;

require_once 'Php2Pg2Php.php';

class Php2Pg2PhpTest
    extends \PHPUnit_Framework_TestCase
{


    public function php2pg_provider()
    {
        return array(
            array
            (
                array(1,2,3),
                '{1,2,3}',
            ),
            array
            (
                array('Hello', 'World!'),
                '{"Hello","World!"}',
            ),
            array
            (
                array('String', 9, 'and', 5, 'Digits'),
                '{"String",9,"and",5,"Digits"}',
            ),
            array
            (
                array( array(1), array(2), array(3) ),
                '{{1},{2},{3}}',
            ),
            array
            (
                array('This is a string with "quotes inside"', '"9"'),
                '{"This is a string with "quotes inside"",""9""}',
            )
        );
    }

    /**
     * @dataProvider php2pg_provider
     */
    public function test_php2pg( $phpArray, $pgArray )
    {
        $output = Php2Pg::Php2Pg( $phpArray );
        $this->assertEquals( $pgArray, $output, "Improper Php2Pg Conversion!" );
    }

    public function pg2php_provider()
    {
        return array(
            array
            (
                '{}',
                array(),
            ),
            array
            (
                '{NULL}',
                array(),
            ),
            array
            (
                '{1,2,3}',
                array(1,2,3),
            ),
            array
            (
                '{"Hello", "World!"}',
                array('Hello', 'World!'),
            ),
            array
            (
                '{{1},{2},{3}}',
                array
                (
                    array(1),
                    array(2),
                    array(3),
                ),
            ),
            array
            (
                '{"Hello, World!"}',
                array('Hello, World!'),
            ),
            array
            (
                '{"This is a string with "quotes inside"",""9""}',
                array( 'This is a string with "quotes inside"', '"9"'),
            )
        );
        }

        /**
     * @dataProvider pg2php_provider
     */
    public function test_pg2php( $pgArray, $phpArray )
    {
        $output = Pg2Php::pg2php( $pgArray );
        $this->assertInternalType( 'array', $phpArray );
        $this->assertInternalType( 'array', $output );
        $this->assertEquals( $phpArray, $output, 'Improper Pg2Php Conversion!');
    }
}
