<?php

namespace Modules\API\Factory\Filters;

use Core\Exceptions\Validation as ValidationException;

class Body {

    const SCHEMA_PATH = __DIR__ . '/schema.yml';

    public static function filterMessage($doc) {

        $schema = yaml_parse_file(self::SCHEMA_PATH);

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
