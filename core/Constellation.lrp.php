<?php
/**
 * Created by ljy
 * User: ljy <sal597685816@live.cn>
 * Date: 2017/8/29
 * Time: 11:30
 */

namespace LunarPHP\Core;

class Constellation
{
    private $log;
    private $db;

    public function __construct()
    {
        $this->log = new Logger();
        $this->db = new Model('constellation');
    }

    /**
     * 获取运势
     */
    public function getFortune($name, $keyWorld, $date)
    {
        if (empty($name)) $this->log->log('获取运势失败：参数[星座]error');

        $keyWorld = '爱情';
        $date = '8月29日';
//        $where = "name like '%{$name}%$keyWorld1%$keyWorld2%'";
        $where = "name like '%{$name}%$keyWorld%'";
        $data = $this->db->query('*', $where);

        return $data[0];
    }
}