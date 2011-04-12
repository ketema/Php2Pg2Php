<?php

namespace Php2Pg2Php;

require_once '../Php2Pg2Php.php';

class Php2Pg2PhpTest
    extends \PHPUnit_Framework_TestCase
{


    public function php2pg_provider()
    {
        return array(
            array( array(1,2,3), '{1,2,3}' ),
            array( array('Hello', 'World!'), '{"Hello","World!"}' ),
            array( array('String', 9, 'and', 5, 'Digits'),
                    '{"String",9,"and",5,"Digits"}' ),
            array( array( array(1), array(2), array(3) ),
                '{{1},{2},{3}}'
            ),
        );    
    }

    /**
     * @dataProvider php2pg_provider
     */
    public function test_php2pg( $phpArray, $pgArray )
    {
        $output = Php2Pg::Php2Pg( $phpArray );
        
        $this->assertEquals(
            $pgArray, $output,
            "Not a Pg array!"
        );
    }

    /**
     * @dataProvider pg2php_provider
     */
    public function test_pg2php( $pgArray, $phpArray )
    {
        $this->assertType

    }
}
