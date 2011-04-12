<?php

namespace Php2Pg2Php;

class Php2Pg
{
    public static function php2pg($phpArray)
    {
        settype($phpArray, 'array');
        $result = array();

        foreach ($phpArray as $element)
        {
            if (is_array($element)) {
                $result[] = Php2Pg::php2pg($element);
            }
            else
            {
                if (! is_numeric( $element ))
                    $element = '"' . $element . '"';
                $result[] = $element;
            }
        }

        return '{' . implode(",", $result) . '}';
    }
}

class Pg2Php
{
    public static function pg2php( $pgArray )
    {
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
                    }

                    $result[] = trim( $element );
                }
            }

            return $result;
         }
    }
}
