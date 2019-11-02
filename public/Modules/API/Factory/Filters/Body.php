<?php

namespace Modules\API\Factory\Filters;

use Core\Exceptions\Validation as ValidationException;

class Body {

    const SCHEMA_PATH = __DIR__ . '/schema.json';

    public static function filterMessage($doc) {

        $schema = json_decode(file_get_contents(self::SCHEMA_PATH));

        $validator = new \JsonSchema\Validator;
        $docObj = json_decode($doc);
        $validator->validate($docObj, $schema);

        if (!$validator->isValid()) {
            $errors = $validator->getErrors();
            $error = $errors[0];
            $message = $error['property'] . ": " . $error['message'];
            throw new ValidationException($message);
        }

        return $docObj;
    }
}
