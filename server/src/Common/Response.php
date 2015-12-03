<?php

namespace FRD\Common;

/**
 * Response class.
 */
class Response
{
    public static function notFound()
    {
        return http_response_code(404);
    }

    public function noContent()
    {
        return http_response_code(204);
    }
}
