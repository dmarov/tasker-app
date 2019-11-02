<?php

namespace Modules\API;
use Core\Exceptions\HTTP as HttpException;
use Core\Exceptions\DB as DBException;
use Core\Exceptions\Validation as ValidationException;

use Rs\Json\Patch;
use Rs\Json\Patch\InvalidPatchDocumentJsonException;
use Rs\Json\Patch\InvalidTargetDocumentJsonException;
use Rs\Json\Patch\InvalidOperationException;

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

        $taskMapper = new \Model\Mappers\Task;

        $task = new \Model\Objects\Task;
        $task->username = $bodyObj->username;
        $task->email = $bodyObj->email;
        $task->text = $bodyObj->text;
        $task->edited = false;

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

    public function patchTask($ctx) {

        $taskMapper = new \Model\Mappers\Task;
        $task = $taskMapper->findById($ctx->params->id);
        if ($task === null)
            throw new HttpException('task not found', 404);

        $targetDoc = json_encode($task);
        $patchDoc = file_get_contents('php://input');

        $patchedTask = new \stdClass;

        try {

            $patch = new Patch($targetDoc, $patchDoc);
            $patchedDoc = $patch->apply();
            $patchedTask = json_decode($patchedDoc);

        } catch (InvalidPatchDocumentJsonException $e) {

            throw new HttpException('invalid patch', 422);
        } catch (InvalidTargetDocumentJsonException $e) {

            throw new HttpException('patch error', 500);
        } catch (InvalidOperationException $e) {

            throw new HttpException('invalid patch', 422);
        }

        $task->username = $patchedTask->username ?? $task->username;
        $task->email = $patchedTask->email ?? $task->email;
        $task->text = $patchedTask->text ?? $task->text;
        $task->edited = $patchedTask->edited ?? $task->edited;

        header('Content-Type: application/hal+json');
        $json = Factory\HAL\Task::get([
            'item' => $task,
        ]);
        die(json_encode($json));
    }

}
