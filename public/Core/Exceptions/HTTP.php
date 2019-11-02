<?php
namespace Core\Exceptions;

class HTTP extends Base {

    private $httpCode;

    public function __construct($message, $httpCode = 500, $code = 0) {

        parent::__construct($message, $code);
        $this->httpCode = $httpCode;
    }

    public function getHttpCode() {

        return (String)$this->httpCode;
    }
}
