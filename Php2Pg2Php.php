<?php

namespace Php2Pg2Php;

class Php2Pg
{
    public static function php2pg($phpArray)
    {
        settype($phpArray, 'array'); // can be called with a scalar or array
        $result = array();

        foreach ($phpArray as $element)
        {
            if (is_array($element)) {
                $result[] = Php2Pg::php2pg($element);
            }
            else
            {
                $element = str_replace('"', '\\"', $element); // escape double quote
                if (! is_numeric( $element )) // quote only non-numeric values
                    $element = '"' . $element . '"';
                $result[] = $element;
            }
        }

        return '{' . implode(",", $result) . '}'; // format
    }
}

class Pg2Php
{
    public static function pg2php( $pgArray )
    {
        if ($pgArray == "'{}'" ||
            empty($pgArray) ||
            $pgArray == '{NULL}' )
        {
             return array();
        }
        else
        {
             $pgArray = trim($pgArray, "{}");
             $phpArray = preg_split('/,(?=([^"]*"[^"]*"[^"]*)*$|[^"]*$)/' ,
                 $pgArray
             );

             for ($i = 0; $i < sizeof( $phpArray ); $i++)
             {
                 $phpArray[$i] = trim( $phpArray[$i], "\"" );
             }

             return $phpArray;
         }
     }
        
    }
}

