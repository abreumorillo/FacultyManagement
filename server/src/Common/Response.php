<?php

namespace FRD\Common;

/**
 * Response class.
 */
class Response
{
    /**
     * Not Found http status code
     * @return [type] [description]
     */
    public static function notFound()
    {
        return http_response_code(404);
    }

    /**
     * Not content HTTP status code
     * @return int
     */
    public static function noContent()
    {
        return http_response_code(204);
    }

    public static function created($data)
    {
        http_response_code(201);
        if(is_array($data)) {
            return $data;
        }
        $response['insertedId'] = $data;
        return $data;
    }

    public static function serverError($data, $message)
    {
        http_response_code(500);
        $response['operationStatus'] = $data;
        $response['message'] = $message;
        return $response;
    }

    /**
     * For internal validation erro
     * @param  array $data
     * @return mix
     */
    public static function validationError($data)
    {
        http_response_code(422); //Validation error
        return $data;
    }

}
