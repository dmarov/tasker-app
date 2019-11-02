<?php

namespace Model\Mappers;

use Model\Objects\Message as MessageObject;
use Core\Exceptions\DB as BDException;
use Latitude\QueryBuilder\QueryFactory;
use function Latitude\QueryBuilder\field;

class Message {

    public function select($params) {

        $pdo = \Core\Db\Connection::getInstance()
            ->getPDO();

        $qb = new QueryFactory; 
        $qb = $qb->select()
            ->from('message');

        $offset = $params['offset'] ?? null;
        $limit = $params['limit'] ?? null;

        if ($offset !== null && $limit !== null) {
            $qb = $qb->offset($offset)
                ->limit($limit);
        }

        $order = $params['order'] ?? ['creation_date' => 'DESC'];
        foreach ($order as $key => $value) {
            $qb = $qb->orderBy($key, $value);
        }

        $query = $qb->compile();
        $stmt = $pdo->prepare($query->sql());
        $stmt->execute($query->params());

        $messages = [];
        $stmt->setFetchMode(\PDO::FETCH_CLASS, MessageObject::class);

        while($row = $stmt->fetch()) {
            $messages[] = $row;
        }

        return $messages;
    }

    public function findById($id) {

        $qb = new QueryFactory; 
        $qb = $qb->select()
            ->from('message')
            ->where(field('id')->eq($id))
            ->limit(1);

        $query = $qb->compile();

        $pdo = \Core\Db\Connection::getInstance()
            ->getPDO();
        $stmt = $pdo->prepare($query->sql());
        $stmt->execute($query->params());

        $stmt->setFetchMode(\PDO::FETCH_CLASS, MessageObject::class);
        $message = $stmt->fetch();

        if ($message !== false) {
            return $message;
        }

        return null;
    }

    public function insert(MessageObject &$message) {

        $qb = new QueryFactory; 
        $qb = $qb->insert('message', [
            'name' => $message->name,
            'surname' => $message->surname,
            'patronymic' => $message->patronymic,
            'email' => $message->email,
            'message' => $message->message,
        ]);

        $query = $qb->compile();

        $pdo = \Core\Db\Connection::getInstance()
            ->getPDO();
        $stmt = $pdo->prepare($query->sql());
        $result = $stmt->execute($query->params());

        if (!$result)
            throw new DBException('message insert failed');

        $id = $pdo->lastInsertId();

        $message = $this->findById($id);
    }

    public function getTotal() {

        $qb = new QueryFactory; 
        $qb = $qb->select('COUNT(*) AS total')
            ->from('message');

        $query = $qb->compile();

        $pdo = \Core\Db\Connection::getInstance()
            ->getPDO();
        $stmt = $pdo->prepare($query->sql());
        $stmt->execute($query->params());

        $result = $stmt->fetch();
        return $result['total'];
    }
}
