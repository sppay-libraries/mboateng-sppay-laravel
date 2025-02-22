<?php

namespace Mboateng\SpPayLaravel\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

class InvalidAccountException extends Exception
{
    private $type = null;

    public function __construct($type = null, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->type = $type;
        $this->message = $this->type ? "Invalid " . strtolower($this->type) . " account" : "Invalid account";
    }

    /**
     * Report the exception.
     *
     */
    public function report()
    {
        // Don't report
    }

    /**
     * Render the exception into an HTTP response.
     *
     */
    public function render(): JsonResponse
    {
        if ($this->type) {

            return response()->json([
                "status" => "error",
                "message" => "Invalid " . strtolower($this->type) . " account"
            ], 400);
        }



        return response()->json([
            "status" => "error",
            "message" => "Invalid account"
        ], 400);
    }
}
