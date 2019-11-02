<?php

namespace Model\Mappers;

use Model\Objects\Task as TaskObject;
use Core\Exceptions\DB as BDException;
use Latitude\QueryBuilder\QueryFactory;
use function Latitude\QueryBuilder\field;

class Task {

    private $tableName = "task";

    public function select($params) {

        $pdo = \Core\Db\Connection::getInstance()
            ->getPDO();

        $qb = new QueryFactory; 
        $qb = $qb->select()
            ->from($this->tableName);

        $offset = $params['offset'] ?? null;
        $limit = $params['limit'] ?? null;

        if ($offset !== null && $limit !== null) {
            $qb = $qb->offset($offset)
                ->limit($limit);
        }

        $order = $params['order'] ?? ['username' => 'ASC'];
        foreach ($order as $key => $value) {
            $qb = $qb->orderBy($key, $value);
        }

        $query = $qb->compile();
        $stmt = $pdo->prepare($query->sql());
        $stmt->execute($query->params());

        $tasks = [];
        $stmt->setFetchMode(\PDO::FETCH_CLASS, TaskObject::class);

        while($row = $stmt->fetch()) {
            $tasks[] = $row;
        }

        return $tasks;
    }

    public function findById($id) {

        $qb = new QueryFactory; 
        $qb = $qb->select()
            ->from($this->tableName)
            ->where(field('id')->eq($id))
            ->limit(1);

        $query = $qb->compile();

        $pdo = \Core\Db\Connection::getInstance()
            ->getPDO();
        $stmt = $pdo->prepare($query->sql());
        $stmt->execute($query->params());

        $stmt->setFetchMode(\PDO::FETCH_CLASS, TaskObject::class);
        $task = $stmt->fetch();

        if ($task !== false) {
            return $task;
        }

        return null;
    }

    public function insert(TaskObject &$task) {

        $qb = new QueryFactory; 
        $qb = $qb->insert('task', [
            'username' => $task->username,
            'email' => $task->email,
            'text' => $task->text,
            'edited' => $task->edited,
            'status' => $task->status,
        ]);

        $query = $qb->compile();

        $pdo = \Core\Db\Connection::getInstance()
            ->getPDO();
        $stmt = $pdo->prepare($query->sql());
        $result = $stmt->execute($query->params());

        if (!$result)
            throw new DBException('task insert failed');

        $id = $pdo->lastInsertId();

        $task = $this->findById($id);
    }

    public function getTotal() {

        $qb = new QueryFactory; 
        $qb = $qb->select('COUNT(*) AS total')
            ->from($this->tableName);

        $query = $qb->compile();

        $pdo = \Core\Db\Connection::getInstance()
            ->getPDO();

        $stmt = $pdo->prepare($query->sql());
        $stmt->execute($query->params());

        $result = $stmt->fetch();
        return (Integer)$result['total'];
    }

    public function update(TaskObject &$task) {

        $qb = new QueryFactory; 
        $qb = $qb->update('task', [
            'username' => $task->username,
            'email' => $task->email,
            'text' => $task->text,
            'edited' => $task->edited,
            'status' => $task->status,
        ])->where(field('id')->eq($task->id));

        $query = $qb->compile();

        $pdo = \Core\Db\Connection::getInstance()
            ->getPDO();
        $stmt = $pdo->prepare($query->sql());
        $result = $stmt->execute($query->params());

        if (!$result)
            throw new DBException('task update failed');

        $id = $pdo->lastInsertId();

        return $this->findById($task->id);
    }
}
