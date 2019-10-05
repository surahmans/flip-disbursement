<?php

namespace App\Database;

use PDOException;

class QueryBuilder
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Insert into database
     *
     * @param string $table
     * @param array $parameters
     * @return array
     */
    public function insert(string $table, array $parameters)
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

    /**
     * Find single record in database
     *
     * @param string $table
     * @param string $column
     * @param string|int $value
     * @return array|null Return null if not found 
     */
    public function findBy(string $table, string $column, $value)
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

    /**
     * Update single record in database
     *
     * @param string $table
     * @param array $parameters
     * @param string $column
     * @param string|int $value
     * @return array|null Return updated data if record is exist. Instead, return null
     */
    public function update(string $table, array $parameters, string $column, $value)
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
