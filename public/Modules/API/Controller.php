<?php

namespace Modules\API;
use Core\Exceptions\HTTP as HttpException;
use Core\Exceptions\DB as DBException;
use Core\Exceptions\Validation as ValidationException;

class Controller {

    public function getTasks($ctx) {

        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();
        $order = Factory\QueryParams::getTasksOrder();

        $taskMapper = new \Model\Mappers\Task;
        $tasks = $taskMapper->select([
            'offset' => $offset,
            'limit' => $limit,
            'order' => $order,
        ]);

        header('Content-Type: application/hal+json');
        $json = Factory\HAL\Tasks::get([
            'items' => $tasks,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $taskMapper->getTotal(),
        ]);

        die(json_encode($json));
    }

    public function getTask($ctx) {

        $taskMapper = new \Model\Mappers\Task;
        $task = $taskMapper->findById($ctx->params->id);
        if ($task === null)
            throw new HttpException('task not found', 404);
        header('Content-Type: application/hal+json');
        $json = Factory\HAL\Task::get([
            'item' => $task,
        ]);
        die(json_encode($json));
    }

    public function appendTask($ctx) {

        $body = file_get_contents('php://input');

        try {
            $bodyObj = Factory\Filters\Body::filterMessage($body);
        } catch(ValidationException $e) {

            throw new HttpException($e->getMessage(), 422);
        }

        $taskMapper = new \Model\Mappers\Message;

        $task = new \Model\Objects\Message;
        $task->name = $bodyObj->name;
        $task->surname = $bodyObj->surname;
        $task->patronymic = $bodyObj->patronymic;
        $task->email = $bodyObj->email;
        $task->task = $bodyObj->task;

        try {
            $taskMapper->insert($task);
        } catch(DBException $e) {
            throw new HttpException($e->getMessage(), 500);
        }

        header('Content-Type: application/hal+json');

        $result = Factory\HAL\Task::get([
            'item' => $task,
        ]);

        die(json_encode($result));
    }
}
