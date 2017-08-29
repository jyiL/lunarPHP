<?php

namespace LunarPHP\Core;

/**
 * Lunar
 */
class Lunar
{
    var $MIN_YEAR = 1891;
    var $MAX_YEAR = 2100;
    var $lunarInfo;

    /**
     * 析构
     */
    public function __construct()
    {
        $this->lunarInfo = json_decode(LUNARINFO,true);
    }

    /**
     * 将阳历转换为阴历
     * @param year 公历-年
     * @param month 公历-月
     * @param date 公历-日
     */
    public function convertSolarToLunar($year,$month,$date)
    {//debugger;
        $yearData = $this->lunarInfo[$year-$this->MIN_YEAR];
        if ( $year == $this->MIN_YEAR && $month <= 2 && $date<=9 ) {
            return array(1891,'正月','初一','辛卯',1,1,'兔');
        }
        return $this->getLunarByBetween($year,$this->getDaysBetweenSolar($year,$month,$date,$yearData[1],$yearData[2]));
    }

    public function convertSolarMonthToLunar($year,$month,$date)
    {
        $yearData = $this->lunarInfo[$year-$this->MIN_YEAR];

        if ( $year == $this->MIN_YEAR && $month <= 2 && $date<=9 ) {
            return array(1891,'正月','初一','辛卯',1,1,'兔');
        }

        $month_days_ary = array(31,28,31,30,31,30,31,31,30,31,30,31);

        $dd = $month_days_ary[$month];

        if ($this->isLeapYear($year) && $month == 2) $dd++;

        $lunar_ary=array();

        for ($i=1;$i<$dd;$i++) {
            $array = $this->getLunarByBetween($year,$this->getDaysBetweenSolar($year,$month,$i,$yearData[1],$yearData[2]));
            $array[] = $year.'-'.$month.'-'.$i;
            $lunar_ary[$i] = $array;
        }
        return $lunar_ary;
    }

    /**
     * 将阴历转换为阳历
     * @param year 阴历-年
     * @param month 阴历-月，闰月处理：例如如果当年闰五月，那么第二个五月就传六月，相当于阴历有13个月，只是有的时候第13个月的天数为0
     * @param date 阴历-日
     */
    public function convertLunarToSolar($year,$month,$date)
    {
        $yearData = $this->lunarInfo[$year-$this->MIN_YEAR];
        $between = $this->getDaysBetweenLunar($year,$month,$date);
        $res = mktime(0,0,0,$yearData[1],$yearData[2],$year);
        $res = date('Y-m-d',$res + $between*24*60*60);
        $day = explode('-',$res);
        $year = $day[0];
        $month = $day[1];
        $day = $day[2];
        return array($year,$month,$day);
    }

    /**
     * 判断是否是闰年
     * @param year
     */
    public function isLeapYear($year)
    {
        return ( ($year%4 == 0 && $year%100 != 0) || ($year%400 == 0) );
    }

    /**
     * 获取干支纪年
     * @param year
     */
    public function getLunarYearName($year)
    {
        $sky = array('庚','辛','壬','癸','甲','乙','丙','丁','戊','己');
        $earth = array('申','酉','戌','亥','子','丑','寅','卯','辰','巳','午','未');
        $year = $year.'';
        return $sky[$year{3}].$earth[$year%12];
    }

    /**
     * 根据阴历年获取生肖
     * @param year 阴历年
     */
    public function getYearZodiac($year)
    {
        $zodiac = array('猴','鸡','狗','猪','鼠','牛','虎','兔','龙','蛇','马','羊');
        return $zodiac[$year%12];
    }

    /**
     * 获取阳历月份的天数
     * @param year 阳历-年
     * @param month 阳历-月
     */
    public function getSolarMonthDays($year,$month)
    {
        $monthHash = array(
            '1'    =>    31,
            '2'    =>    $this->isLeapYear($year) ? 29 : 28,
            '3'    =>    31,
            '4'    =>    30,
            '5'    =>    31,
            '6'    =>    30,
            '7'    =>    31,
            '8'    =>    31,
            '9'    =>    30,
            '10'   =>    31,
            '11'   =>    30,
            '12'   =>    31
        );

        return $monthHash["$month"];
    }

    /**
     * 获取阴历月份的天数
     * @param year 阴历-年
     * @param month 阴历-月，从一月开始
     */
    public function getLunarMonthDays($year,$month)
    {
        $monthData = $this->getLunarMonths($year);
        return $monthData[$month-1];
    }

    /**
     * 获取阴历每月的天数的数组
     * @param year
     */
    public function getLunarMonths($year)
    {
        $yearData = $this->lunarInfo[$year-$this->MIN_YEAR];

        $leapMonth = $yearData[0];

        $bit = decbin($yearData[3]);
        for ($i=0; $i<strlen($bit); $i ++) {
            $bitArray[$i] = substr($bit,$i,1);
        }

        for ($k=0, $klen = 16-count($bitArray); $k<$klen; $k++){
            array_unshift($bitArray,'0');
        }

        $bitArray = array_slice($bitArray,0,($leapMonth==0?12:13));
        for ($i=0; $i<count($bitArray); $i++) {
            $bitArray[$i]=$bitArray[$i] + 29;
        }

        return $bitArray;
    }

    /**
     * 获取农历每年的天数
     * @param year 农历年份
     */
    public function getLunarYearDays($year)
    {
        $yearData = $this->lunarInfo[$year-$this->MIN_YEAR];
        $monthArray = $this->getLunarYearMonths($year);
        $len = count($monthArray);
        return ($monthArray[$len-1]==0?$monthArray[$len-2]:$monthArray[$len-1]);
    }

    /**
     * 获取农历每年的月份
     * @param string $year 农历年份
     */
    public function getLunarYearMonths($year)
    {//debugger;
        $monthData = $this->getLunarMonths($year);
        $res = array();
        $temp = 0;
        $yearData = $this->lunarInfo[$year-$this->MIN_YEAR];
        $len = ($yearData[0]==0?12:13);

        for ($i=0;$i<$len;$i++) {
            $temp=0;
            for ($j=0;$j<=$i;$j++) {
                $temp+=$monthData[$j];
            }
            array_push($res,$temp);
        }

        return $res;
    }

    /**
     * 获取闰月
     * @param year 阴历年份
     */
    public function getLeapMonth($year)
    {
        $yearData = $this->lunarInfo[$year-$this->MIN_YEAR];
        return $yearData[0];
    }

    /**
     * 计算阴历日期与正月初一相隔的天数
     * @param year
     * @param month
     * @param date
     */
    public function getDaysBetweenLunar($year,$month,$date)
    {
        $yearMonth = $this->getLunarMonths($year);
        $res = 0;
        for ($i=1; $i<$month; $i++) {
            $res += $yearMonth[$i-1];
        }
        $res += $date-1;
        return $res;
    }

    /**
     * 计算2个阳历日期之间的天数
     * @param year 阳历年
     * @param cmonth
     * @param cdate
     * @param dmonth 阴历正月对应的阳历月份
     * @param ddate 阴历初一对应的阳历天数
     */
    public function getDaysBetweenSolar($year,$cmonth,$cdate,$dmonth,$ddate)
    {
        $a = mktime(0,0,0,$cmonth,$cdate,$year);
        $b = mktime(0,0,0,$dmonth,$ddate,$year);
        return ceil(($a-$b)/24/3600);
    }

    /**
     * 根据距离正月初一的天数计算阴历日期
     * @param year 阳历年
     * @param between 天数
     */
    public function getLunarByBetween($year,$between)
    {//debugger;
        $lunarArray = array();
        $yearMonth = array();
        $t = 0;
        $e = 0;
        $leapMonth = 0;
        $m = '';
        if ($between == 0) {
            array_push($lunarArray,$year,'正月','初一');
            $t = 1;
            $e = 1;
        } else {
            $year = $between>0? $year : ($year-1);
            $yearMonth = $this->getLunarYearMonths($year);
            $leapMonth = $this->getLeapMonth($year);
            $between = $between>0?$between : ($this->getLunarYearDays($year)+$between);
            for ($i=0; $i<13; $i++) {
                if ($between == $yearMonth[$i]) {
                    $t = $i+2;
                    $e = 1;
                    break;
                } else if ($between < $yearMonth[$i]){
                    $t = $i+1;
                    $e = $between-(empty($yearMonth[$i-1])?0:$yearMonth[$i-1])+1;
                    break;
                }
            }
            $m = ($leapMonth!=0&&$t==$leapMonth+1)?('闰'.$this->getCapitalNum($t- 1,true)):$this->getCapitalNum(($leapMonth!=0&&$leapMonth+1<$t?($t-1):$t),true);
            array_push($lunarArray,$year,$m,$this->getCapitalNum($e,false));
        }
        array_push($lunarArray,$this->getLunarYearName($year));// 天干地支
        array_push($lunarArray,$t,$e);
        array_push($lunarArray,$this->getYearZodiac($year));// 12生肖
        array_push($lunarArray,$leapMonth);// 闰几月
        return $lunarArray;
    }

    /**
     * 获取数字的阴历叫法
     * @param num 数字
     * @param isMonth 是否是月份的数字
     */
    public function getCapitalNum($num,$isMonth)
    {
        $isMonth = $isMonth||false;
        $dateHash = array('0'=>'','1'=>'一','2'=>'二','3'=>'三','4'=>'四','5'=>'五','6'=>'六','7'=>'七','8'=>'八','9'=>'九','10'=>'十 ');
        $monthHash = array('0'=>'','1'=>'正月','2'=>'二月','3'=>'三月','4'=>'四月','5'=>'五月','6'=>'六月','7'=>'七月','8'=>'八月','9'=>'九月','10'=>'十月','11'=>'冬月','12'=>'腊月');
        $res = '';
        if ($isMonth) {
            $res = $monthHash[$num];
        } else {
            if ($num <= 10) {
                $res = '初'.$dateHash[$num];
            } else if ($num>10&&$num<20){
                $res = '十'.$dateHash[$num-10];
            } else if ($num==20){
                $res = "二十";
            } else if ($num>20&&$num<30){
                $res = "廿".$dateHash[$num-20];
            } else if ($num==30){
                $res = "三十";
            }
        }
        return $res;
    }
}