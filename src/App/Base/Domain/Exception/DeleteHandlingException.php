<?php

namespace CTIC\App\Base\Domain\Exception;

class DeleteHandlingException extends \Exception
{
    /**
     * @var string
     */
    protected $flash;

    /**
     * @var int
     */
    protected $apiResponseCode;

    /**
     * @param string $message
     * @param string $flash
     * @param int $apiResponseCode
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(
        string $message = 'Ups, something went wrong during deleting a resource, please try again.',
        string $flash = 'something_went_wrong_error',
        int $apiResponseCode = 500,
        int $code = 0,
        ?\Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->flash = $flash;
        $this->apiResponseCode = $apiResponseCode;
    }

    /**
     * @return string
     */
    public function getFlash(): string
    {
        return $this->flash;
    }

    /**
     * @return int
     */
    public function getApiResponseCode(): int
    {
        return $this->apiResponseCode;
    }
}
