<?php
/**
 * Created by PhpStorm.
 * User: Lzw
 * Date: 2019/12/26
 * Time: 21:01
 * Use : 构造SQL语句
 */

namespace monkey\db\builder;


trait SQLBuilder
{
    /**
     * 限定操作符
     * @var array
     */
    protected $operator = ['=', '>', '<>', '<', 'like', '!=', '<=', '>=', '+', '-', '/', '*', '%', 'IS NULL', 'IS NOT NULL', 'LEAST', 'GREATEST', 'BETWEEN', 'IN', 'NOT BETWEEN', 'NOT IN', 'REGEXP', 'IS', 'IS NOT',];

    /**
     * 默认查询关键字
     * @var array
     */
    protected $querySequence = ['union', 'select', 'join', 'where', 'group', 'having', 'order', 'limit',];

    /**
     * 默认删除语句关键字
     * @var array
     */
    protected $deleteSequence = ['delete', 'join', 'where', 'limit',];

    /**
     * 默认更新语句关键字
     * @var array
     */
    protected $updateSequence = ['update', 'where', 'order', 'limit',];

    /**
     * 默认添加语句关键字
     * @var array
     */
    protected $insertSequence = ['insert'];

    protected $joinSequence = ['inner','left','right'];

    /**
     * 主表表名
     * @var string
     */
    protected $table;

    /**
     * 表前缀
     * @var string
     */
    protected $prefix;

    /**
     * 字段容器
     * @var string
     */
    protected $columns;

    /**
     * 处理后的条件语句
     * @var string
     */
    protected $wheres = '';

    /**
     * 处理后的join语句
     * @var string
     */
    protected $joins = '';

    /**
     * 处理后的group语句
     * @var string
     */
    protected $group = '';

    /**
     * 处理后的having语句
     * @var string
     */
    protected $having = '';

    /**
     * 处理后的order语句
     * @var string
     */
    protected $order = '';

    /**
     * 处理后的limit条件语句
     * @var string
     */
    protected $offset = '';
    protected $limit = '';

    /**
     * 处理前的更新、添加数据语句
     * @var array
     */
    protected $beforeDealInfo = [];

    /**
     * 处理后的更新、添加语句
     * @var string
     */
    protected $info = '';


    #-----------------------------
    # SQL语句关键字处理
    #-----------------------------

    private function createUnion()
    {
        $sql = '';
        return $sql;
    }

    private function createSelect()
    {
        $columns = explode(',',$this->columns);
        foreach ($columns as &$column){
            $column = $this->dealColumns($column);
        }
        $columns = implode(',',$columns);
        $sql = 'SELECT ' . $columns . ' FROM ' . $this->table;
        return $sql;
    }

    private function createJoin()
    {
        return $this->joins;
    }

    private function createWhere()
    {
        return empty($this->wheres) ? '' : ' WHERE ' . $this->wheres;
    }

    private function createGroup()
    {
        return empty($this->group) ? '' : ' GROUP BY ' . $this->group;
    }

    private function createHaving()
    {
        return empty($this->having) ? '' : ' HAVING ' . $this->having;
    }

    private function createOrder()
    {
        return empty($this->order) ? '' : ' ORDER BY ' . $this->order;
    }

    private function createLimit()
    {
        return empty($this->limit) ? '' : ' LIMIT ' . $this->limit;
    }

    private function createDelete()
    {
        $sql = 'DELETE FROM ' . $this->table;
        return $sql;
    }

    private function createUpdate()
    {
        $sql = "UPDATE " . $this->table . " SET " . $this->info;
        return $sql;
    }

    private function createInsert()
    {
        $sql = "INSERT INTO " . $this->table . ' ' . $this->info;
        return $sql;
    }

    #-----------------------------
    # where/having条件、更新数据 处理
    #-----------------------------

    /**
     * 处理数组类型的where条件
     * @param array $condition
     * @param string $link where连接类型
     * @return string
     */
    private function dealArrayWhere(array $condition,string $link = 'and')
    {
        $where = [];
        foreach ($condition as $key => $value){
            if(is_int($key)){
                $where[] = $value;
            }else {
                // 拼接where语句
                $where[] = $this->dealColumns($key) . "=:$key";
                // 设置参数
                $this->params[":$key"] = $value;
            }
        }
        $link = empty($this->wheres) ? '' : strtoupper($link) . ' ';
        $where = $link . '(' . implode(' AND ',$where) . ')';
        return $where;
    }

    /**
     * 创建where AND 条件语句
     * @param string|array $condition
     * @param string $operator
     * @param string $value
     * @param string $link
     * @return string
     */
    private function dealWhere($condition,$operator = '',$value = '',string $link = 'and')
    {
        // 拼接where语句
        $where = $this->dealColumns($condition) . $operator . ":$condition";
        // 设置参数
        $this->params[":$condition"] = $value;
        $link = empty($this->wheres) ? '' : strtoupper($link) . ' ';
        return $link . "($where)";
    }

    /**
     * 处理更新数据
     * @param array $info
     * @return string
     */
    private function dealUpdateInfo(array $info)
    {
        $update = [];
        foreach ($info as $key => $value){
            $update[] = "`$key`=:$key";
            $this->params[":$key"] = $value;
        }
        return implode(',',$update);
    }

    /**
     * 处理添加数据
     * @param array $insertInfo
     * @return string
     */
    private function dealInsertInfo(array $insertInfo)
    {
        $field = [];
        $info = [];
        foreach ($insertInfo as $key => $value){
            $field[] = $this->dealColumns($key);
            $info[] = ":$key";
            $this->params[":$key"] = $value;
        }
        $field = implode(',',$field);
        $info = implode(',',$info);
        return "($field) VALUES($info)";
    }

    /**
     * 处理数组类型的having条件
     * @param array $condition
     * @param string $link
     * @return string
     */
    private function dealArrayHaving(array $condition,string $link = 'and')
    {
        $having = [];
        foreach ($condition as $key => $value){
            if(is_int($key)){
                $having[] = $value;
            }else{
                $having[] = "$key = $value";
            }
        }
        return implode(strtoupper($link),$having);
    }

    /**
     * 处理having条件
     * @param $condition
     * @param string $operator
     * @param string $value
     * @param $link
     * @return string
     */
    private function dealHaving($condition,$operator,$value,$link)
    {
        $link = empty($this->having) ? '' : $link;
        return $link . $condition . $operator . $value;
    }

    #-----------------------------
    # where条件、更新数据 处理
    #-----------------------------

    /**
     * 处理数组类型的 join 条件
     * @param string $table
     * @param array $condition
     * @param string $link
     * @return string
     */
    private function dealArrayJoin(string $table,array $condition,string $link)
    {
        $join = [];
        foreach ($condition as $key => $value){
            if(is_int($key)){
                $join[] = $value;
            }else{
                $join[] = $key . '=' . $value;
            }
        }
        $join = implode(' AND ',$join);
        return strtoupper($link) . ' JOIN ' . $table . ' ON ' . $join;
    }

    /**
     * 处理join条件
     * @param string $table
     * @param string $field
     * @param string|null $operator
     * @param string|null $secondField
     * @param string $link
     * @return string
     */
    private function dealJoin(string $table,string $field,string $operator = null,string $secondField = null,string $link = '')
    {
        return strtoupper($link) . ' JOIN ' . $table . ' ON ' . $field . $operator . $secondField;
    }

    /**
     * 字段处理
     * @param string $columns
     * @return string
     */
    private function dealColumns(string $columns)
    {
        if($columns == '*'){
            return $columns;
        }
        return implode('.',array_map(function($key){
            return "`$key`";
        },explode('.',$columns)));
    }

    /**
     * 判断操作符合法性
     * @param string $operator
     * @return bool
     */
    private function isOperator(string $operator)
    {
        return in_array(strtoupper($operator),$this->operator);
    }
}