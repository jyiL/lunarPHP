<?php

namespace LunarPHP\Core;

/**
 * 数据库模型
 * User: ljy
 * Date: 17-08-17
 */
class Model
{
    private $db;
    private $_table = '';
    private $log;

    public function __construct($table)
    {
        $this->_table = $table;

        $db_config = json_decode(DB_CONFIG,true);

        $this->log = new Logger();

        $dsn = $db_config['dbms'].":host=".$db_config['serverName'].";dbname=".$db_config['dbName'];

        try {
            $this->db = new \PDO($dsn, $db_config['user'], $db_config['pass'], array(\PDO::ATTR_PERSISTENT => true));
        } catch (\PDOException $e) {
            $this->log->log('数据库连接失败：'.$e->getMessage());
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }

    /**
     * 查询
     */
    public function query($filed = array(),$where = '')
    {
        if (!empty($where) && !empty($filed)) {
            if (is_array($where)) {
                $where = $this->where($where);
            }
            $sql = sprintf("SELECT %s FROM `%s` WHERE %s", $this->fields($filed), $this->_table, $where);
        } else {
            $sql = sprintf("SELECT %s FROM `%s`", $this->fields($filed), $this->_table);
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        if (!empty($data)) {
            return $data;
        } else {
            return false;
        }
    }

    /**
     * 处理字段
     */
    private function fields($data)
    {
        if ($data == '*') {
            return $data;
        } else {
            foreach ($data as $key => $value) {
                $fileds[] = '`'.$value.'`';
            }

            $str = implode(',', $fileds);
            return $str;
        }
    }

    /**
     * 设置条件语句
     */
    private function where($where)
    {
        $str = '';
        $i = 1;
        foreach ($where as $key => $value) {
            if( $i == 1) {
                $str .= '`'.$key.'` = '."'".$value."'";
            } else {
                $str .= ' AND `'.$key.'` = '."'".$value."'";
            }
            $i++;
        }

        return $str;
    }
}

