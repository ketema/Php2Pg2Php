<?php

/**
 * Converts arrays between {@link http://www.postgresql.org PostgreSQL} and {@link http://www.php.net PHP} formats.
 *
 * @package Php2Pg2Php
 * @author Ketema Harris <ketema@ketema.net>
 * @version 1.0
 * @license BSD
 *
 * Copyright 2011 Ketema Harris All rights reserved.
 *
 *  Redistribution and use in source and binary forms, with or without modification, are
 *  permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice, this list of
 *  conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice, this list
      of conditions and the following disclaimer in the documentation and/or other materials
 *    provided with the distribution.

 * THIS SOFTWARE IS PROVIDED BY <COPYRIGHT HOLDER> ``AS IS'' AND ANY EXPRESS OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND
 * FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
 * ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

namespace Php2Pg2Php;

require_once 'Php2Pg.php';
require_once 'Pg2Php.php';

/**
 * Tests the Php2Pg2Php namespace
 */
class Php2Pg2PhpTest
    extends \PHPUnit_Framework_TestCase
{

    // @codeCoverageIgnoreStart
    /**
     * Provides data to the php2pg test
     *
     * @return void
     */
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
    // @codeCoverageIgnoreEnd

    /**
     * Tests the php2pg method
     *
     * @dataProvider php2pg_provider
     * @return void
     */
    public function test_php2pg( $phpArray, $pgArray )
    {
        $output = Php2Pg::Php2Pg( $phpArray );
        $this->assertEquals( $pgArray, $output, "Improper Php2Pg Conversion!" );
    }

    // @codeCoverageIgnoreStart
    /**
     * Provides data to the pg2php test
     *
     * @return void
     */
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
    // @codeCoverageIgnoreEnd

    /**
     * Tests the pg2php method
     *
     * @dataProvider pg2php_provider
     * @return void
     */
    public function test_pg2php( $pgArray, $phpArray )
    {
        $output = Pg2Php::pg2php( $pgArray );
        $this->assertInternalType( 'array', $phpArray );
        $this->assertInternalType( 'array', $output );
        $this->assertEquals( $phpArray, $output, 'Improper Pg2Php Conversion!');
    }
}
