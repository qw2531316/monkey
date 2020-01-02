<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2019/12/25
 * Time: 12:29
 * Use : 连接底层接口
 */

namespace monkey\db\connect;

interface ConnectionInterface
{
    /**
     * 创建PDO
     * @param array $config
     * @return \PDO
     */
    public function setPdo(array $config);

    /**
     * 获取PDO
     * @return \PDO
     */
    public function getPdo();

    /**
     * 获取最后一次插入数据库的ID
     * @return mixed
     */
    public function lastInsertId();

    /**
     * 事务
     * @return void
     */
    public function transaction();

    /**
     * 事务回滚
     * @return void
     */
    public function rollback();

    /**
     * 事务提交
     * @return void
     */
    public function commit();

    /**
     * 执行查询SQL
     * @param string $sql
     * @param array $condition
     * @return \PDOStatement
     */
    public function executeSqlReturnStatement(string $sql, array $condition = []);

    /**
     * 执行SQL操作
     * @param string $sql
     * @param array $condition
     * @return \PDOStatement
     */
    public function executeSqlReturn(string $sql,array $condition = []);

    /**
     * 绑定参数并执行SQL
     * @param \PDOStatement $pdo
     * @param array $condition
     * @return mixed
     */
    public function executeSqlCommon(\PDOStatement $pdo, array $condition = []);

    /**
     * 获取所有结果集
     * @param \PDOStatement $pdo
     * @return mixed
     */
    public function getAll(\PDOStatement $pdo);

    /**
     * 获取一个结果
     * @param \PDOStatement $pdo
     * @return mixed
     */
    public function getOne(\PDOStatement $pdo);

    /**
     * 执行操作SQL语句
     * @param \PDOStatement $pdo
     * @return int
     */
    public function execute(\PDOStatement $pdo);

    /**
     * 设置结果集的数据类型
     * @param int $fetchType
     * @return void
     */
    public function setFetchType(int $fetchType = \PDO::FETCH_OBJ);
}