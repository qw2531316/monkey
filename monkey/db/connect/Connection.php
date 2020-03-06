<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2019/12/25
 * Time: 13:24
 * Use : PDO连接底层及数据操作
 */

namespace monkey\db\connect;

use Monkey;
use PDO;
use PDOStatement;

class Connection implements ConnectionInterface
{
    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * 返回结果集数据类型
     * @var int
     */
    protected $fetchType;

    /**
     * 创建PDO
     * @param array $config
     * @return ConnectionInterface
     * @throws \Exception
     */
    public function setPdo(array $config)
    {
        $this->setFetchType(empty($config['fetchType']) ? PDO::FETCH_OBJ : $config['fetchType']);
        $dns = $config['dns'] ?: $config['driverName'] . ":host=" . $config['host'] . ";port=" . $config['port'] . ";dbname=" . $config['dbname'];
        $username = $config['username'];
        $password = $config['password'];
        try{
            $pdo = new PDO($dns,$username,$password);
            $this->pdo = $pdo;
        }catch (\PDOException $e){
            Monkey::error($e->getMessage());
            throw new \Exception("数据库加载异常！");
        }
        return $this;
    }

    /**
     * 获取PDO
     * @return PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * 获取最后一次插入数据库的ID
     * @return mixed
     */
    public function lastInsertId()
    {
        return $this->pdo->query('SELECT CAST(COALESCE(SCOPE_IDENTITY(), @@IDENTITY) AS bigint)')->fetchColumn();
    }

    /**
     * 事务
     * @return void
     */
    public function transaction()
    {
        $this->pdo->beginTransaction();
    }

    /**
     * 事务回滚
     * @return void
     */
    public function rollback()
    {
        $this->pdo->rollBack();
    }

    /**
     * 事务提交
     * @return void
     */
    public function commit()
    {
        $this->pdo->commit();
    }

    /**
     * 执行SQL返回PDOStatement
     * @param string $sql
     * @param array $condition
     * @return PDOStatement
     */
    public function executeSqlReturnStatement(string $sql, array $condition = [])
    {
        $statement = $this->pdo->prepare($sql);
        $this->executeSqlCommon($statement,$condition);
        return $statement;
    }

    /**
     * 执行SQL操作
     * @param string $sql
     * @param array $condition
     * @return mixed
     */
    public function executeSqlReturn(string $sql, array $condition = [])
    {
        $statement = $this->pdo->prepare($sql);
        return $this->executeSqlCommon($statement,$condition);
    }

    /**
     * 绑定参数并执行SQL
     * @param PDOStatement $pdo
     * @param array $condition
     * @return bool
     */
    public function executeSqlCommon(PDOStatement $pdo, array $condition = [])
    {
        if(!empty($condition)) {
            foreach ($condition as $key => $value) {
                $pdo->bindValue($key, $value, is_integer($value) ? PDO::PARAM_INT : (is_null($value) ?: PDO::PARAM_STR));
            }
        }
        try{
            //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $result = $pdo->execute();
        }catch(\Exception $e){
            Monkey::sqlLog($e->getMessage());
            return false;
        }
        return $result;
    }

    /**
     * 获取所有结果集
     * @param PDOStatement $pdo
     * @return mixed
     */
    public function getAll(PDOStatement $pdo)
    {
        return $pdo->fetchAll($this->fetchType);
    }

    /**
     * 获取一个结果
     * @param PDOStatement $pdo
     * @return mixed
     */
    public function getOne(PDOStatement $pdo)
    {
        return $pdo->fetch($this->fetchType);
    }

    /**
     * 执行操作SQL语句
     * @param \PDOStatement $pdo
     * @return int
     */
    public function execute(\PDOStatement $pdo)
    {
        return $pdo->rowCount();
    }

    /**
     * 设置结果集的数据类型
     * @param int $fetchType
     * @return void
     */
    public function setFetchType(int $fetchType = PDO::FETCH_OBJ)
    {
        $this->fetchType = $fetchType;
    }
}