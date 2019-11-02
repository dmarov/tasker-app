<?php

namespace Modules\API;
use Core\Exceptions\HTTP as HttpException;
use Core\Exceptions\DB as DBException;
use Core\Exceptions\Validation as ValidationException;

class Controller {

    public function __construct() {

        $router = \Core\Router::getInstance();

        $router->add('GET', '/api/messages', [$this, 'getMessages'], 'api:messages');
        $router->add('POST', '/api/messages', [$this, 'appendMessage']);
        $router->add('GET', '/api/messages/:id', [$this, 'getMessage'], 'api:message');
    }

    public function getMessages($ctx) {

        try {

            $offset = Factory\QueryParams::getOffset();
            $limit = Factory\QueryParams::getLimit();
            $order = Factory\QueryParams::getMessagesOrder();

            $messageMapper = new \Model\Mappers\Message;
            $messages = $messageMapper->select([
                'offset' => $offset,
                'limit' => $limit,
                'order' => $order,
            ]);

            header('Content-Type: application/hal+json');
            $json = Factory\HAL\Messages::get([
                'items' => $messages,
                'offset' => $offset,
                'limit' => $limit,
                'total' => $messageMapper->getTotal(),
            ]);

            die(json_encode($json));

        } catch (\Exception $e) {

            header('Content-Type: application/json');
            http_response_code(500);
            die(json_encode([
                'errors' => [
                    ['message' => $e->getMessage()],
                ],
            ]));
        }
    }

    public function appendMessage($ctx) {

        try {
            $body = file_get_contents('php://input');

            try {
                $bodyObj = Factory\Filters\Body::filterMessage($body);
            } catch(ValidationException $e) {

                throw new HttpException($e->getMessage(), 422);
            }

            $messageMapper = new \Model\Mappers\Message;

            $message = new \Model\Objects\Message;
            $message->name = $bodyObj->name;
            $message->surname = $bodyObj->surname;
            $message->patronymic = $bodyObj->patronymic;
            $message->email = $bodyObj->email;
            $message->message = $bodyObj->message;

            try {
                $messageMapper->insert($message);
            } catch(DBException $e) {
                throw new HttpException($e->getMessage(), 500);
            }

            header('Content-Type: application/hal+json');

            $result = Factory\HAL\Message::get([
                'item' => $message,
            ]);

            die(json_encode($result));

        } catch (HttpException $e) {

            header('Content-Type: application/json');
            http_response_code($e->getHttpCode());
            die(json_encode([
                'errors' => [
                    ['message' => $e->getMessage()],
                ],
            ]));

        } catch (\Exception $e) {

            header('Content-Type: application/json');
            http_response_code(500);
            die(json_encode([
                'errors' => [
                    ['message' => $e->getMessage()],
                ],
            ]));
        }
    }

    public function getMessage($ctx) {

        try {

            $messageMapper = new \Model\Mappers\Message;
            $message = $messageMapper->findById($ctx->params->id);
            if ($message === null)
                throw new HttpException('message not found', 404);
            header('Content-Type: application/hal+json');
            $json = Factory\HAL\Message::get([
                'item' => $message,
            ]);
            die(json_encode($json));

        } catch (HttpException $e) {

            header('Content-Type: application/json');
            http_response_code($e->getHttpCode());
            die(json_encode([
                'errors' => [
                    ['message' => $e->getMessage(), 'httpCode' => $e->getHttpCode()],
                ],
            ]));

        } catch (\Exception $e) {

            header('Content-Type: application/json');
            http_response_code(500);
            die(json_encode([
                'errors' => [
                    ['message' => $e->getMessage(), 'httpCode' => 500],
                ],
            ]));
        }
    }
}
