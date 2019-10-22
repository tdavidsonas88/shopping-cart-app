<?php
/**
 * Created by PhpStorm.
 * User: tadas
 * Date: 19.10.22
 * Time: 21.50
 */

namespace Service;


class FileReaderService
{
    /**
     * @param string $fileName
     * @return string
     */
    public static function readFileIntoArray(string $fileName)
    {
        /** @var string $lines */
        $lines = file($fileName, FILE_IGNORE_NEW_LINES);
        return $lines;
    }



}