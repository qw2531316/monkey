<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2019/12/25
 * Time: 23:06
 * Use : DBQuery构造
 */

namespace monkey\db\builder;

use Monkey;
use monkey\db\connect\ConnectionInterface;

class QueryBuilder
{
    use SQLBuilder;

    /**
     * 数据库操作容器
     * @var ConnectionInterface
     */
    protected $connect;

    /**
     * SQL语句
     * @var string
     */
    protected $sql;

    /**
     * 绑定参数容器
     * @var array
     */
    protected $params = [];

    /**
     * 用到的SQL语句关键字
     * @var array
     */
    protected $sequence = [];

    /**
     * 加载数据库依赖注入PDO底层
     * QueryBuilder constructor.
     * @param ConnectionInterface $connect
     */
    public function __construct(ConnectionInterface $connect)
    {
        $this->connect = $connect;
    }

    /**
     * 获取数据库操作容器
     * @return ConnectionInterface
     */
    public function getConnect()
    {
        return $this->connect;
    }

    #-----------------------------
    # 设置表名、表前缀
    #-----------------------------

    /**
     * 指定表
     * @param string $table
     * @param string $alias
     * @return $this
     */
    public function table(string $table,string $alias = '')
    {
        $prefix = $this->getPrefix();
        if(!empty($alias)) {
            $aliasArr = preg_split('/\s/', str_replace(',', ' ', $alias));
            if (isset($aliasArr[1])) {
                $alias = '';
            }
        }
        $this->table = trim($prefix . $table . ' ' . $alias);
        return $this;
    }

    /**
     * 表前缀
     * @return string
     */
    private function getPrefix()
    {
        $prefix = 'monkey_' ?: '';
        return $prefix;
    }

    #-----------------------------
    # 构造SQL语句
    #-----------------------------

    /**
     * 创建SQL
     * @param array $sequence
     * @return string
     */
    private function createSQL($sequence = [])
    {
        if (empty($this->sql)) {
            $this->sequence = array_unique($this->sequence);
            $sequence = array_intersect($sequence,$this->sequence);
            foreach ($sequence as $value) {
                $this->sql .= trim($this->{'create' . ucfirst($value)}()) . ' ';
            }
        }
        return $this->sql;
    }

    #-----------------------------
    # 查询字段、where条件处理
    #-----------------------------

    /**
     * 设置查询字段
     * @param string $columns
     * @return $this
     */
    public function select(string $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * 设置条件
     * @param array|string $condition
     * @param null $operator
     * @param null $value
     * @param string $link
     * @return $this
     */
    public function where($condition,$operator = null,$value = null,string $link = 'and')
    {
        $this->sequence[] = 'where';
        if(is_array($condition)){
            $this->wheres .= $this->dealArrayWhere($condition,$link);
            return $this;
        }
        if(empty($operator)){
            $this->wheres .= empty($this->wheres) ? $condition : " $link " . $condition;
        }else {
            if (empty($value) && !$this->isOperator($operator)) {
                $value = $operator;
                // 默认操作符
                $operator = '=';
            }
            if(!$this->isOperator($operator)){
                $operator = '=';
            }
            $this->wheres .= $this->dealWhere($condition, $operator, $value, $link);
        }
        return $this;
    }

    /**
     * 条件 AND 补充
     * @param $condition
     * @param null $operator
     * @param null $value
     * @return $this
     */
    public function andWhere($condition,$operator = null,$value = null)
    {
        return $this->where($condition,$operator,$value);
    }

    /**
     * 条件 OR 补充 （Ps : 需要在 where 之后，否则默认 AND 处理)
     * @param $condition
     * @param null $operator
     * @param null $value
     * @return $this
     */
    public function orWhere($condition,$operator = null,$value = null)
    {
        return $this->where($condition,$operator,$value,'or');
    }

    #-----------------------------
    # 联表查询
    #-----------------------------

    /**
     * 联表查询
     * @param string $link
     * @param string $table
     * @param string|array $field
     * @param string|null $operator
     * @param string|null $secondField
     * @return $this
     */
    public function join(string $link,string $table,$field,string $operator = null,string $secondField = null)
    {
        if(!in_array(strtolower($link),$this->joinSequence)){
            $message = 'JOIN类型不能使用 ' . $link;
            Monkey::$app->log->error($message);
            throw new \InvalidArgumentException($message);
        }
        $prefix = $this->getPrefix();
        $table = $prefix . $table;
        $this->sequence[] = 'join';
        if(is_array($field)){
            $this->joins .= $this->dealArrayJoin($table,$field,$link);
            return $this;
        }
        if(empty($operator)){
            $this->joins .= strtoupper($link) . ' JOIN ' . $table . ' ON ' . $field;
            return $this;
        }
        // $operator 作为第二参数条件
        if(!$this->isOperator($operator) && empty($secondField)){
            $secondField = $operator;
            $operator = '=';
        }
        // 默认连接符
        if(!$this->isOperator($operator)){
            $operator = '=';
        }
        $this->joins .= $this->dealJoin($table,$field,$operator,$secondField,$link);
        return $this;
    }

    /**
     * 左连接查询
     * @param string $table
     * @param string|array $field
     * @param string|null $operator
     * @param string|null $secondField
     * @return QueryBuilder
     */
    public function leftJoin(string $table,$field,string $operator = null,string $secondField = null)
    {
        return $this->join('left',$table,$field,$operator,$secondField);
    }

    /**
     * 右连接查询
     * @param string $table
     * @param string|array $field
     * @param string|null $operator
     * @param string|null $secondField
     * @return QueryBuilder
     */
    public function rightJoin(string $table,$field,string $operator = null,string $secondField = null)
    {
        return $this->join('right',$table,$field,$operator,$secondField);
    }

    /**
     * 内连接查询
     * @param string $table
     * @param string|array $field
     * @param string|null $operator
     * @param string|null $secondField
     * @return QueryBuilder
     */
    public function innerJoin(string $table,$field,string $operator = null,string $secondField = null)
    {
        return $this->join('inner',$table,$field,$operator,$secondField);
    }

    #-----------------------------
    # 限制条件
    #-----------------------------

    /**
     * 分组查询
     * @param string|array $field 字段名
     * @return $this
     */
    public function group($field)
    {
        $this->sequence[] = 'group';
        if(is_array($field)){
            $field = implode(',',$field);
        }
        $this->group = $field;
        return $this;
    }

    public function having($condition,$operator = null,$value = null,$link = 'and')
    {
        $this->sequence[] = 'having';
        if(is_array($condition)){
            $this->having .= $this->dealArrayHaving($condition,$link);
            return $this;
        }
        $argvNum = func_num_args();
        if($argvNum == 1){
            $this->having .= $condition;
            return $this;
        }
        if($argvNum == 2 && !$this->isOperator($operator)){
            $value = $operator;
            $operator = '=';
        }
        $this->having .= $this->dealHaving($condition,$operator,$value,$link);
        return $this;
    }

    public function orHaving($condition,$operator = null,$value = null)
    {
        $this->sequence[] = 'having';
        return $this->having($condition,$operator,$value,'or');
    }

    /**
     * 排序规则
     * @param $field
     * @param string $rule
     * @return $this
     */
    public function order($field,$rule = '')
    {
        $this->sequence[] = 'order';
        if(!empty($rule)){
            // 防止错误传参数 eg: order('id asc,username asc','asc');
            $fieldArr = preg_split('/\s/',str_replace(',',' ',$field));
            if(isset($fieldArr[1]) && in_array(strtoupper($fieldArr[1]),['ASC','DESC'])){
                $rule = '';
            }
        }
        $this->order = $field . ' ' . $rule;
        return $this;
    }

    /**
     * 限制查询行数
     * @param $limit
     * @param string $offset
     * @return $this
     */
    public function limit($limit,$offset = '')
    {
        $this->sequence[] = 'limit';
        $this->offset = $offset;
        $this->limit = empty($offset) ? $limit : $offset . ',' . $limit;
        return $this;
    }

    /**
     * 限制查询起始位置
     * @param $offset
     * @return $this
     */
    public function offset($offset)
    {
        $this->sequence[] = 'limit';
        // 防止先调用limit函数
        $this->limit = empty($this->limit) && empty($this->offset) ? '' : $offset . ',' . $this->limit;
        $this->offset = $offset;
        return $this;
    }

    #-----------------------------
    # CURD
    #-----------------------------

    /**
     * 获取所有数据
     * @return mixed
     */
    public function get()
    {
        $this->sequence[] = 'select';
        return $this->createCommand($this->createSQL($this->querySequence), $this->params, function ($pdo) {
            return $this->getConnect()->getAll($pdo);
        });
    }

    /**
     * QueryBuilder::get() 别名
     * @return mixed
     */
    public function all()
    {
        return $this->get();
    }

    /**
     * 查找一行数据
     * @return mixed
     */
    public function getOne()
    {
        $this->sequence[] = 'select';
        return $this->createCommand($this->createSQL($this->querySequence), $this->params, function ($pdo) {
            return $this->getConnect()->getOne($pdo);
        });
    }

    /**
     * 返回查询结果的value值
     * @return mixed
     */
    public function getScalar()
    {
        $result = $this->getOne();
        return array_values($result)[0];
    }

    /**
     * 更新、添加数据;
     * Ps : 只接收键值对一维数组[field => value]
     * @param array $info
     * @return $this
     */
    public function info(array $info)
    {
        $this->beforeDealInfo = $info;
        return $this;
    }

    /**
     * 更新数据
     * @return int
     */
    public function update()
    {
        $this->sequence[] = 'update';
        $this->info = $this->dealUpdateInfo($this->beforeDealInfo);
        return $this->execute($this->updateSequence);
    }

    /**
     * 添加数据
     * @return int
     */
    public function insert()
    {
        $this->sequence[] = 'insert';
        $this->info = $this->dealInsertInfo($this->beforeDealInfo);
        return $this->execute($this->insertSequence);
    }

    /**
     * 获取最新一次添加成功数据的ID
     * @return int
     */
    public function lastInsertId()
    {
        return $this->getConnect()->lastInsertId();
    }

    /**
     * 删除数据
     * @return int
     */
    public function delete()
    {
        $this->sequence[] = 'delete';
        return $this->execute($this->deleteSequence);
    }

    /**
     * 执行操作语句
     * @param array $sequence
     * @return int
     */
    private function execute($sequence = [])
    {
        return $this->createCommand($this->createSQL($sequence),$this->params,function($pdo){
            return $this->getConnect()->execute($pdo);
        });
    }

    #-----------------------------
    # 执行SQL
    #-----------------------------

    /**
     * 执行sql
     * @param string $sql
     * @param array $params
     * @param \Closure $closure
     * @return mixed
     */
    private function createCommand(string $sql,array $params,\Closure $closure)
    {
        $this->sql = '';
        return $closure(
            // 执行SQL
            $this->getConnect()->executeSqlReturnStatement($sql,$params)
        );
    }

    /**
     * 直接执行完整的SQL
     * @param string $sql
     * @param array $params
     * @return $this
     */
    public function createCommandBySQL(string $sql,array $params = [])
    {
        $this->sql = $sql;
        $this->params = $params;
        return $this;
    }

    #-----------------------------
    # 事务
    #-----------------------------

    /**
     * 开启事务
     */
    public function transaction()
    {
        $this->getConnect()->transaction();
    }

    /**
     * 事务提交
     */
    public function commit()
    {
        $this->getConnect()->commit();
    }

    /**
     * 事务回滚
     */
    public function rollback()
    {
        $this->getConnect()->rollback();
    }
}