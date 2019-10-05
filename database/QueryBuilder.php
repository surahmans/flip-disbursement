<?php

namespace App\Database;

use PDOException;
use ReflectionClass;

class QueryBuilder
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function insert($table, $parameters)
    {
        $sql = sprintf(
            'insert into %s (%s) values (%s)',
            $table,
            implode(', ', array_keys($parameters)),
            ':' . implode(', :', array_keys($parameters))
        );

        try {
            $query = $this->pdo->prepare($sql);
            $query->execute($parameters);
            $lastId = $this->pdo->lastInsertId();

            return $this->findBy($table, 'id', $lastId);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function findBy($table, $column, $value)
    {
        $sql = sprintf('select * from %s where %s = ?', $table, $column);

        try {
            $query = $this->pdo->prepare($sql);
            $query->execute([$value]);

            $result = $query->fetch();

            return $result === false ? null : $result;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function update($table, $parameters, $column, $value)
    {
        $sql = sprintf(
            'update %s set %s where %s = ?',
            $table,
            implode('=?, ', array_keys($parameters)) . '=? ',
            $column
        );

        try {
            $query = $this->pdo->prepare($sql);
            $query->execute(array_merge(array_values($parameters), [$value]));

            return $this->findBy($table, $column, $value);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
