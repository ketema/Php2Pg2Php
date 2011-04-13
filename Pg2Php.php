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

/**
 * Convert a Pg array string retrieved from a query into a php array.
 */
class Pg2Php
{
    /**
     * Convert a Pg array string retrieved from a query into a php array.
     *
     * @param string $pgArray Pg array string
     *
     * @return array A php array
     */
    public static function pg2php( $pgArray )
    {
        $doubleQuote = <<<'DOUBLEQUOTE'
"
DOUBLEQUOTE;
        $pgArray = trim( $pgArray );

        if ( $pgArray == '{}' || empty( $pgArray ) || $pgArray == '{NULL}' )
        {
            return array();
        }
        else
        {

            $matches = array();
            if ( preg_match('/^{(.*)}$/', $pgArray, $matches) > 0 )
            {
                $pgString = $matches[1];
            }
            else
            {
                $pgString = $pgArray;
            }

            /**
             * RegEx using back references so that we can split on comma
             * but still get any commas that may be inside double quotes
             * i.e. a string element of the Pg array
             */
            $phpArray = preg_split('/,(?=([^"]*"[^"]*"[^"]*)*$|[^"]*$)/' , $pgString );

            foreach( $phpArray as $element )
            {
                $element = trim($element);
                if ( preg_match( '/^{.*}$/', $element))
                {
                    $result[] = Pg2Php::pg2php( trim( $element, '{}' ) );
                }
                else
                {
                    $matches = array();
                    if ( preg_match('/^"(.*)"$/', $element, $matches) > 0 )
                    {
                        $element = $matches[1];
                        //Need to determine if it has a serializd object inside
                        try
                        {
                            if( preg_match( '/\\\"/', $element ) )
                            {
                                $element = preg_replace( '/\\\"/', $doubleQuote, $element );
                            }
                            $var = unserialize( "$element" );
                            if( is_object( $var ) )
                            {
                                $element = $var;
                            }
                        }
                        catch (Exception $e ) { }
                    }

                    $result[] = trim( $element );
                }
            }

            return $result;
         }
    }
}
