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
 * Convert a php array into a Pg array string.
 */
class Php2Pg
{

    const PGESC = "E'";

    /**
     * Convert a php array into a Pg array string.
     *
     * @param array $phpArray A php array.
     *
     * @return string    A Pg array string.
     */
    public static function php2pg( $phpArray )
    {
        if( is_object( $phpArray) )
        {
            $phpArray = array( $phpArray );
        }

        settype( $phpArray, 'array' );

        //Check for objects in the array
        foreach( $phpArray as $key => $value )
        {
            if( is_object( $value ) )
            {
                $phpArray[$key] = str_replace( '"', '\\"', serialize( $value ) );
            }
        }

        $result = array();

        foreach( $phpArray as $element )
        {
            if( is_array( $element ) ){
                $matches = array();
                preg_match( "/^E'(.*)'$/", Php2Pg::php2pg( $element ), $matches );
                $result[] = $matches[1];
            }
            else
            {
                $element = str_replace( '"', '\\"', $element );
                if (! is_numeric( $element ) )
                    $element = '"' . $element . '"';
                $result[] = $element;
            }
        }

        return Php2Pg::PGESC . '{' . implode(",", $result) . '}'. "'";
    }
}
