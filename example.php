<?php

require_once 'LunarPHP.php';

use LunarPHP\Core\Lunar;
use LunarPHP\Core\Calendar;
use LunarPHP\Core\Model;
use LunarPHP\Core\Hexagrams;
use LunarPHP\Core\Logger;
use LunarPHP\Core\Constellation;

class example
{
    private $calendar;
    private $lunar;
    private $hexagrams;
    private $log;
    private $date;
    private $year;
    private $month;
    private $day;
    private $DZ = array('Y','M','D');
    public $error;
    private $db;
    public $hour;
    private $hourList;
    public $constellation;

    public function __construct( $date, $hour = '子时' )
    {
        $this->date = $date;    // 阳历日期
        $this->year = date("Y",strtotime($date));    // 年
        $this->month = date("m",strtotime($date));    // 月
        $this->day = date("d",strtotime($date));    // 日
        $this->calendar = new Calendar($this->date);
        $this->lunar = new Lunar();
        $this->hexagrams = new Hexagrams();
        $this->log = new Logger();
        $this->db = new Model('gua');
        $this->constellation = new Constellation();

        $this->hourList = json_decode(HOUR,true);    // 时辰数组
        $this->hour = $this->hourList[$hour];
    }

    /**
     * 获取天干地支
     * @param string $type Y：地支纪年    M：地支纪月    D：地支纪日
     * @param boolean $suffix 是否显示后缀
     * return string
     */
    public function getGanZhi( $type, $suffix = true )
    {
        $type = is_string($type) ? strtoupper($type) : false;
        if ( !in_array($type, $this->DZ) ) {
            $this->log->log('获取天干地支传递参数类型错误');
            return false;
        }

        $suffixTitle = '';

        if ($suffix) {
            $suffixTitle = array(
                'Y' =>  '年',
                'M' =>  '月',
                'D' =>  '日',
            )[$type];
        }

        switch ($type) {
            case 'Y':
                return $this->calendar->getYGanZhi().$suffixTitle;
                break;
            case 'M':
                return $this->calendar->getMGanZhi().$suffixTitle;
                break;
            case 'D':
                return $this->calendar->getDGanZhi().$suffixTitle;
                break;
        }
    }

    /**
     * 阳->阴
     * @return array
     */
    public function convertSolarToLunar()
    {
        $data = $this->lunar->convertSolarToLunar($this->year, $this->month, $this->day);

        $res['convertCYear'] = $data[0];
        $res['convertCMonth'] = $data[1];
        $res['convertCDay'] = $data[2];
        $res['yearText'] = $this->getGanZhi('y',0);
        $res['monthText'] = $this->calendar->getMGanZhi(mb_substr($res['yearText'], 0, 1, 'utf-8'), $data[4]);
        $res['dayText'] = $this->getGanZhi('d',0);
        $res['convertNYear'] = $data[7];
        $res['convertNMonth'] = $data[4];
        $res['convertNDay'] = $data[5];
        $res['animal'] = $data[6];

        return $res;
    }

    /**
     * 获取卦象
     */
    public function getDisplay()
    {
        $data = $this->convertSolarToLunar();

        $res['originYear'] = $this->year;
        $res['originMonth'] = $this->month;
        $res['originDay'] = $this->day;
        $res['hour'] = $this->hour;
        $res['convertYear'] = $data['convertCYear'];
        $res['convertMonth'] = $data['convertNMonth'];
        $res['convertDay'] = $data['convertNDay'];
        $res['yearText'] = $data['yearText'];
        $res['monthText'] = $data['monthText'];
        $res['dayText'] = $data['dayText'];
        $res['animal'] = $data['animal'];
        $res['type'] = 'destiny';

        return $this->disposalArray($this->getRow($this->hexagrams->getDisplay($res)));
    }

    /**
     * 获取卦象结果
     * @param array $data 卦象数组
     * @return array
     */
    private function getRow($data)
    {
        $keyWorld = '婚姻';

        /** 判断有没有变卦 **/
        if ( array_key_exists('convert',$data) && !empty($data['convert']) ) {
            $where = "name like '%{$data['convert']}%$keyWorld%'";

            $tmp = $this->db->query('*', $where);

            if (!$tmp) {
                $where = "name like '%{$data['convert']}%'";
                $tmp = $this->db->query('*', $where);
            }

            $res['name'] = $data['convert'];
        } else {
            $where = "name like '%{$data['origin']}%$keyWorld%'";
            $tmp = $this->db->query($where);

            if (!$tmp) {
                $where = "name like '%{$data['origin']}%'";
                $tmp = $this->db->query($where);
            }

            $res['name'] = $data['origin'];
        }

        $sfHexagrams = '';

        foreach ($tmp as $val) {
            $sfHexagrams .= $val['content'];
        }

        $res['content'] = $sfHexagrams;

        return $res;
    }


    /**
     * 数组处理
     * @param array $arr
     * @return array
     */
    private function disposalArray($arr)
    {
        $arr['content'] = preg_replace('/&#13;/','',$arr['content']);

        $arr['content'] = preg_replace('#<div([\s\S])(.*)<\/div>#is', '',$arr['content']);

        $arr['content']  = preg_replace("#\s|　#","",$arr['content']);

        $arr['content'] = strip_tags($arr['content']);

        $content = preg_split( "#【(.*?)】+#",$arr['content'] );

        preg_match_all( "#【(.*?)】+#",$arr['content'],$title );

        unset($content[0]);

        $tmp = array_combine($title[0],$content);

        foreach ($tmp as $title => $content) {
            $data[] = array(
                'title' =>  $title,
                'content' =>  $content,
            );
        }

        return array(
            'name'  =>  $arr['name'],
            'dataList'  =>  $data,
        );
    }
}

echo '<pre>';

/** 阳历日期 **/
$date = '1992-01-15';

/** 时辰 **/
$hour = '子时';    // 子时 丑时 寅时 卯时 辰时 巳时 午时 未时 申时 酉时 戌时 亥时

/** 实例化 **/
$example = new example($date, $hour);    // 不传时辰默认子时

/** 获取卦象结果 **/
$gua = $example->getDisplay();

/** 打印结果 **/
var_dump($gua);

exit;








