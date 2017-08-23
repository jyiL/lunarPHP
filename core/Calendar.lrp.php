<?php

namespace LunarPHP\Core;

require_once 'GanZhi.php';

/**
 *author:dequan
 *date:2016-06-20
 *原文地址:http://blog.csdn.net/hsd2012/article/details/51701640
 */
class Calendar
{
    private $animals;
    private $curData = null;//当前阳历时间
    private $ylYeal = 0;
    private $ylMonth = 0;
    private $yldate = 0;
    private $ylDays = 0; //当前日期是农历年的第多少天
    private $leap = 0;//代表润哪一个月
    private $leapDays = 0;//代表闰月的天数
    private $difmonth = 0;//当前时间距离参考时间相差多少月
    private $difDay = 0;//当前时间距离参考时间相差多少天
    private $tianGan;
    private $diZhi;
    private $yearGan;
    private $monthGan;
    private $dataInfo;

    public function __construct($curData=null)
    {
        $this->animals = json_decode(ANIMALS,true);
        $this->tianGan = json_decode(TIANGAN,true);
        $this->diZhi = json_decode(DIZHI,true);
        $this->yearGan = json_decode(YEARGAN,true);
        $this->monthGan = json_decode(MONTHGAN,true);
        $this->dataInfo = json_decode(DATAINFO,true);

        if ( !empty($curData) ) {
            $this->curData = $curData;
        } else {
            $this->curData = date('Y-n-j');
        }

        $this->init();
    }

    public function init()
    {
        $basedate = '1900-1-31';//参照日期
        $timezone = 'PRC';
        $datetime = new \DateTime($basedate, new \DateTimeZone($timezone));
        $curTime = new \DateTime($this->curData, new \DateTimeZone($timezone));
        $offset   = ($curTime->format('U') - $datetime->format('U'))/86400; //相差的天数
        $offset = ceil($offset);
        $this->difDay = $offset;
        $offset += 1;//只能使用ceil，不能使用intval或者是floor,因为1900-1-31为正月初一，故需要加1

        for ($i=1900; $i<2050 && $offset>0; $i++) {
            $temp = $this->getYearDays($i); //计算i年有多少天
            $offset -= $temp ;
            $this->difmonth+=12;
            //判断该年否存在闰月
            if ($this->leapMonth($i)>0) {
                $this->difmonth+=1;
            }
        }

        if ($offset<0) {
            $offset += $temp;
            $i--;
            $this->difmonth-=12;
        }

        if ($this->leapMonth($i)>0) {
            $this->difmonth-=1;
        }

        $this->ylDays = $offset;

        //此时$offset代表是农历该年的第多少天
        $this->ylYeal = $i;//农历哪一年

        //计算月份，依次减去1~12月份的天数，直到offset小于下个月的天数
        $curMonthDays = $this->monthDays($this->ylYeal,1);

        //判断是否该年是否存在闰月以及闰月的天数
        $this->leap = $this->leapMonth($this->ylYeal);

        if ($this->leap !=0) {
            $this->leapDays = $this->leapDays($this->ylYeal);
        }

        for ($i=1; $i<13 && $curMonthDays < $offset; $curMonthDays = $this->monthDays($this->ylYeal,++$i)) {
            if ($this->leap == $i) { //闰月
                if ($offset>$this->leapDays) {
                    --$i;
                    $offset -= $this->leapDays;
                    $this->difmonth+=1;
                } else {
                    break;
                }
            } else {
                $offset -= $curMonthDays;
                $this->difmonth += 1;
            }
        }

        $this->ylMonth = $i;
        $this->yldate = $offset;
    }

    /**
     *计算农历y年有多少天
     **/
    public function getYearDays($y)
    {
        $sum = 348;//12*29=348,不考虑小月的情况下

        for ($i=0x8000; $i>=0x10; $i>>=1) {
            $sum += ($this->dataInfo[$y-1900] & $i)? 1: 0;
        }

        return ( $sum + $this->leapDays($y) );
    }

    /**
     * 获取某一年闰月的天数
     */
    public function leapDays($y)
    {
        if ( $this->leapMonth($y) ) {
            return ( ($this->dataInfo[$y-1900] & 0x10000) ? 30 : 29 );

        } else {
            return(0);
        }
    }

    /**
     * 计算哪一月为闰月
     */
    public function leapMonth($y)
    {
        return ($this->dataInfo[$y-1900] & 0xf);
    }

    /**
     * 计算农历y年m月有多少天
     */
    public function monthDays($y,$m)
    {
        return (($this->dataInfo[$y-1900] & (0x10000>>$m))? 30: 29 );
    }

    /**
     * getLyTime
     */
    public function getLyTime()
    {
        $tmp = array('初','一','二','三','四','五','六','七','八','九','十','廿');

        $dateStr='';

        if ($this->ylMonth > 10) {
            $m2 = intval($this->ylMonth -10); //十位
            $dateStr = '十'.$tmp[$m2].'月';
        } elseif ($this->ylMonth == 1) {
            $dateStr = '正月';
        } else {
            $dateStr = $tmp[$this->ylMonth].'月';
        }

        if ($this->yldate <11) {
            $dateStr .= '初'.$tmp[$this->yldate];
        } else {
            $m1 = intval($this->yldate / 10);
            if ( $m1 !=3) {
                $dateStr .= ($m1==1) ? '十' : '廿';
                $m2 = $this->yldate % 10;
                if ($m2==0) {
                    $dateStr.='十';
                } else {
                    $dateStr.=$tmp[$m2];
                }
            } else {
                $dateStr.='三十';
            }
        }

        return $dateStr;
    }

    /**
     * 获取该年对于的天干地支年
     */
    public function getYGanZhi()
    {
        $gan = $this->tianGan[($this->ylYeal-4) % 10];
        $zhi = $this->diZhi[($this->ylYeal-4) % 12];
        return $gan.$zhi;
    }

    /**
     * 获取该年对于的天干地支月
     *
     */
    public function getMGanZhi($yGan,$convertMonth)
    {
        /*$gan=$this->tianGan[($this->difmonth+4) % 10];
        $zhi=$this->diZhi[($this->difmonth+10) % 12];
        return $gan.$zhi;*/
        $GanNum = '';
        foreach ($this->yearGan as $k => $Gan) {
            if ( in_array($yGan,$Gan) ) {
                $GanNum = $k;
            }
        }
        return $this->monthGan[$GanNum][$convertMonth];
    }

    /**
     * 获取该年对于的天干地支日
     */
    public function getDGanZhi()
    {
        $gan = $this->tianGan[$this->difDay % 10];
        $zhi = $this->diZhi[($this->difDay+4) % 12];
        return $gan.$zhi;
    }
}