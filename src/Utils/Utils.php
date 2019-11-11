<?php
/**
 * Created by PhpStorm.
 * User: tadas
 * Date: 19.11.11
 * Time: 10.06
 */

class Utils
{

    public static function floatsAreEqual(float $a, float $b)
    {
        $epsilon = 0.00001;
        if (abs($a - $b) < $epsilon) {
            return true;
        }
        return false;
    }
}