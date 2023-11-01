<?php

namespace App\Enums;

use Symfony\Component\HttpFoundation\Response;

class StatusCodes
{
    public const SUCCESS = 200;
    public const CREATED = 201;
    public const METHOD_NOT_ALLOWED = 405;
    public const NOT_FOUND = 404;
    public const FORBIDDEN = 403;
    public const BAD_REQUEST = 400;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;
}
