<?php

namespace App\Exceptions;

use Exception;

/**
 * Class BasicWrapperException
 *
 * This class represents a custom exception to throw when something goes wrong.
 * Until a more complex exception handling is needed this is fine for the few cases when we trow an exception.
 */
class BasicWrapperException extends Exception
{
    /**
     * Report the exception.
     */
    public function report(): void
    {
        // You can log the exception or perform any other action here
        \Log::error($this->getMessage(), ['exception' => $this]);
    }
}
