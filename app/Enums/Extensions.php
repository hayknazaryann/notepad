<?php

namespace App\Enums;

class Extensions
{
    public const PDF = 'pdf';
    public const DOC = 'doc';
    public const DOCX = 'docx';

    public static function all()
    {
        return [
            self::PDF, self::DOC, self::DOCX
        ];
    }

}
