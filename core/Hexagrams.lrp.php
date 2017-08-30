<?php

namespace LunarPHP\Core;

/**
 * Hexagrams
 */
class Hexagrams
{
    public $error;
    private $lang;

    public function __construct()
    {
        $this->lang = json_decode(LANG,true);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getDisplay($params)
    {
        if ( !array_key_exists('originYear', $params) || !array_key_exists('originMonth', $params) ||
            !array_key_exists('originDay', $params) || !array_key_exists('hour', $params) ||
            !array_key_exists('type', $params) || !array_key_exists('convertYear', $params) ||
            !array_key_exists('convertMonth', $params) || !array_key_exists('convertDay', $params) ||
            !array_key_exists('yearText', $params) || !array_key_exists('monthText', $params) ||
            !array_key_exists('dayText', $params) || !array_key_exists('animal', $params) ) {
            $this->error = '参数错误';
            return false;
        }

        $event = $this->getEvent($params);

        $symbol = ( $params['type'] == 'destiny' ) ? $this->getDestiny($params, $event) : $this->getDivine($params, $event);

        return $symbol;
    }


    /**
     * getEvent
     */
    private function getEvent($urlParam)
    {
        $result = array();


        $emptyType = array();
        $skyIndex = 1;
        $skyText = mb_substr($urlParam['dayText'], 0, 1);
        $groundIndex = 1;
        $groundText = mb_substr($urlParam['dayText'], 1);
        switch ( $skyText ) {
            case $this->lang['home_sky2']:
                $skyIndex = 2;
                break;

            case $this->lang['home_sky3']:
                $skyIndex = 3;
                break;

            case $this->lang['home_sky4']:
                $skyIndex = 4;
                break;

            case $this->lang['home_sky5']:
                $skyIndex = 5;
                break;

            case $this->lang['home_sky6']:
                $skyIndex = 6;
                break;

            case $this->lang['home_sky7']:
                $skyIndex = 7;
                break;

            case $this->lang['home_sky8']:
                $skyIndex = 8;
                break;

            case $this->lang['home_sky9']:
                $skyIndex = 9;
                break;

            case $this->lang['home_sky10']:
                $skyIndex = 10;
                break;

            case $this->lang['home_sky1']:
            default:
                $skyIndex = 1;
                break;
        }
        switch ($groundText) {
            case $this->lang['home_ground2']:
                $groundIndex = 2;
                break;

            case $this->lang['home_ground3']:
                $groundIndex = 3;
                break;

            case $this->lang['home_ground4']:
                $groundIndex = 4;
                break;

            case $this->lang['home_ground5']:
                $groundIndex = 5;
                break;

            case $this->lang['home_ground6']:
                $groundIndex = 6;
                break;

            case $this->lang['home_ground7']:
                $groundIndex = 7;
                break;

            case $this->lang['home_ground8']:
                $groundIndex = 8;
                break;

            case $this->lang['home_ground9']:
                $groundIndex = 9;
                break;

            case $this->lang['home_ground10']:
                $groundIndex = 10;
                break;

            case $this->lang['home_ground11']:
                $groundIndex = 11;
                break;

            case $this->lang['home_ground12']:
                $groundIndex = 12;
                break;

            case $this->lang['home_sky1']:
            default:
                $groundIndex = 1;
                break;
        }
        $groundResult = $groundIndex - $skyIndex;
        if ( $groundResult == 1 ) {
            $emptyType[1] = $this->lang["home_ground1"];
            $emptyType[12] = $this->lang["home_ground12"];
        } else if ( $groundResult <= 0 ) {
            $groundResult = 12 + $groundResult;
            $emptyType[$groundResult-1] = $this->lang["home_ground" . ($groundResult-1)];
            $emptyType[$groundResult] = $this->lang["home_ground" . $groundResult];
        } else {
            $emptyType[$groundResult-1] = $this->lang["home_ground" . ($groundResult-1)];
            $emptyType[$groundResult] = $this->lang["home_ground" . $groundResult];
        }
        $result['emptyType'] = $emptyType;
        $emptyType = join(', ', $emptyType);

        $brokenType = array();
        $existType = array(mb_substr($urlParam['yearText'], 1), mb_substr($urlParam['monthText'], 1), mb_substr($urlParam['dayText'], 1), mb_substr($this->lang["home_hour{$urlParam['hour']}"], 0, 1));
        if ( in_array($this->lang['home_ground1'], $existType) ) {
            $brokenType[7] = $this->lang['home_ground7'];
        }
        if ( in_array($this->lang['home_ground2'], $existType) ) {
            $brokenType[8] = $this->lang['home_ground8'];
        }
        if ( in_array($this->lang['home_ground3'], $existType) ) {
            $brokenType[9] = $this->lang['home_ground9'];
        }
        if ( in_array($this->lang['home_ground4'], $existType) ) {
            $brokenType[10] = $this->lang['home_ground10'];
        }
        if ( in_array($this->lang['home_ground5'], $existType) ) {
            $brokenType[11] = $this->lang['home_ground11'];
        }
        if ( in_array($this->lang['home_ground6'], $existType) ) {
            $brokenType[12] = $this->lang['home_ground12'];
        }
        if ( in_array($this->lang['home_ground7'], $existType) ) {
            $brokenType[1] = $this->lang['home_ground1'];
        }
        if ( in_array($this->lang['home_ground8'], $existType) ) {
            $brokenType[2] = $this->lang['home_ground2'];
        }
        if ( in_array($this->lang['home_ground9'], $existType) ) {
            $brokenType[3] = $this->lang['home_ground3'];
        }
        if ( in_array($this->lang['home_ground10'], $existType) ) {
            $brokenType[4] = $this->lang['home_ground4'];
        }
        if ( in_array($this->lang['home_ground11'], $existType) ) {
            $brokenType[5] = $this->lang['home_ground5'];
        }
        if ( in_array($this->lang['home_ground12'], $existType) ) {
            $brokenType[6] = $this->lang['home_ground6'];
        }
        $result['brokenType'] = $brokenType;
        $brokenType = join(', ', $brokenType);

        $result['html'] = "<div class='event'>
            <span>" . $this->lang['home_empty'] . ": {$emptyType}</span>
            <span>" . $this->lang['home_broken'] . ": {$brokenType}</span>
        </div>";


        return $result;
    }


    /**
     * 時辰起卦
     */
    private function getDestiny($urlParam, $event)
    {
        $monthType = $urlParam['convertMonth'] % 8;
        $monthType = ( $monthType == 0 ) ? 8 : $monthType;
        $dayType = $urlParam['convertDay'] % 8;
        $dayType = ( $dayType == 0 ) ? 8 : $dayType;
        $hourType = $urlParam['hour'] % 6;
        $hourType = ( $hourType == 0 ) ? 6 : $hourType;


        $animal = $this->getAnimal(mb_substr($urlParam['dayText'], 0, 1));
        $originUpSymbol = $this->getSymbol($dayType);
        $originDownSymbol = $this->getSymbol($monthType);
        $originFamily = $this->getFamily($dayType, $monthType, $event['emptyType'], $event['brokenType']);
        $soul = $this->getSoul($hourType);
        $convertSymbol = $this->getConvertSymbol($dayType, $monthType, $hourType, $event);
        $convertInfo = ( !$convertSymbol ) ? '' : $convertSymbol['symbolName'];

        $result['origin'] = $originFamily['symbolName'];
        $result['convert'] = $convertInfo;

        return $result;
    }


    /**
     * 獲取生肖
     */
    private function getAnimal($dayText)
    {
        $currIndex = 1;
        switch ( $dayText ) {
            case $this->lang['home_sky3']:
            case $this->lang['home_sky4']:
                $currIndex = 2;
                break;

            case $this->lang['home_sky5']:
                $currIndex = 3;
                break;

            case $this->lang['home_sky6']:
                $currIndex = 4;
                break;

            case $this->lang['home_sky7']:
            case $this->lang['home_sky8']:
                $currIndex = 5;
                break;

            case $this->lang['home_sky9']:
            case $this->lang['home_sky10']:
                $currIndex = 6;
                break;

            case $this->lang['home_sky1']:
            case $this->lang['home_sky2']:
            default:
                $currIndex = 1;
                break;
        }

        $temp = array();
        for ( $i=0; $i<6; $i++ ) {
            if ( $currIndex > 6 ) {
                $currIndex = 1;
            }

            $temp[] = "<div>" . $this->lang["home_animal{$currIndex}"] . "</div>";

            $currIndex++;
        }
        krsort($temp);

        $result = "<div class='animal'>" . join('', $temp) . "</div>";


        return $result;
    }


    private function getSymbol($type)
    {
        $temp = '';
        switch ( $type ) {
            case 1:
                $temp = "<div>&ndash;&mdash;&mdash;&ndash;</div>
                         <div>&ndash;&mdash;&mdash;&ndash;</div>
                         <div>&ndash;&mdash;&mdash;&ndash;</div>";
                break;

            case 2:
                $temp = "<div>&ndash;&ndash; &nbsp; &nbsp;&ndash;&ndash;</div>
                         <div>&ndash;&mdash;&mdash;&ndash;</div>
                         <div>&ndash;&mdash;&mdash;&ndash;</div>";
                break;

            case 3:
                $temp = "<div>&ndash;&mdash;&mdash;&ndash;</div>
                         <div>&ndash;&ndash; &nbsp; &nbsp;&ndash;&ndash;</div>
                         <div>&ndash;&mdash;&mdash;&ndash;</div>";
                break;

            case 4:
                $temp = "<div>&ndash;&ndash; &nbsp; &nbsp;&ndash;&ndash;</div>
                         <div>&ndash;&ndash; &nbsp; &nbsp;&ndash;&ndash;</div>
                         <div>&ndash;&mdash;&mdash;&ndash;</div>";
                break;

            case 5:
                $temp = "<div>&ndash;&mdash;&mdash;&ndash;</div>
                         <div>&ndash;&mdash;&mdash;&ndash;</div>
                         <div>&ndash;&ndash; &nbsp; &nbsp;&ndash;&ndash;</div>";
                break;

            case 6:
                $temp = "<div>&ndash;&ndash; &nbsp; &nbsp;&ndash;&ndash;</div>
                         <div>&ndash;&mdash;&mdash;&ndash;</div>
                         <div>&ndash;&ndash; &nbsp; &nbsp;&ndash;&ndash;</div>";
                break;

            case 7:
                $temp = "<div>&ndash;&mdash;&mdash;&ndash;</div>
                         <div>&ndash;&ndash; &nbsp; &nbsp;&ndash;&ndash;</div>
                         <div>&ndash;&ndash; &nbsp; &nbsp;&ndash;&ndash;</div>";
                break;

            case 8:
            default:
                $temp = "<div>&ndash;&ndash; &nbsp; &nbsp;&ndash;&ndash;</div>
                         <div>&ndash;&ndash; &nbsp; &nbsp;&ndash;&ndash;</div>
                         <div>&ndash;&ndash; &nbsp; &nbsp;&ndash;&ndash;</div>";
                break;
        }
        $result = "<div class='symbol'>{$temp}</div>";


        return $result;
    }

    private function getFamily($upSymbolType, $downSymbolType, $emptyType, $brokenType)
    {
        $result = array();

        $emptys = array();
        foreach ( $emptyType as $k=>$et ) {
            $emptys[$k] = "&curren;";
        }
        $brokens = array();
        foreach ( $brokenType as $k=>$bt ) {
            $brokens[$k] = "&#9888;";
        }


        if ( $upSymbolType == 1 && $downSymbolType == 1 ) {
            $result['symbolProperty'] = $this->lang['home_property1'];
            $result['symbolName'] = $this->lang['home_symbol1'];

        } else if ( $upSymbolType == 1 && $downSymbolType == 7 ) {
            $result['symbolProperty'] = $this->lang['home_property1'];
            $result['symbolName'] = $this->lang['home_symbol2'];

        } else if ( $upSymbolType == 5 && $downSymbolType == 8 ) {
            $result['symbolProperty'] = $this->lang['home_property1'];
            $result['symbolName'] = $this->lang['home_symbol3'];

        } else if ( $upSymbolType == 3 && $downSymbolType == 8 ) {
            $result['symbolProperty'] = $this->lang['home_property1'];
            $result['symbolName'] = $this->lang['home_symbol4'];

        } else if ( $upSymbolType == 1 && $downSymbolType == 5 ) {
            $result['symbolProperty'] = $this->lang['home_property1'];
            $result['symbolName'] = $this->lang['home_symbol5'];

        } else if ( $upSymbolType == 1 && $downSymbolType == 8 ) {
            $result['symbolProperty'] = $this->lang['home_property1'];
            $result['symbolName'] = $this->lang['home_symbol6'];

        } else if ( $upSymbolType == 7 && $downSymbolType == 8 ) {
            $result['symbolProperty'] = $this->lang['home_property1'];
            $result['symbolName'] = $this->lang['home_symbol7'];

        } else if ( $upSymbolType == 3 && $downSymbolType == 1 ) {
            $result['symbolProperty'] = $this->lang['home_property1'];
            $result['symbolName'] = $this->lang['home_symbol8'];

        } else if ( $upSymbolType == 2 && $downSymbolType == 2 ) {
            $result['symbolProperty'] = $this->lang['home_property1'];
            $result['symbolName'] = $this->lang['home_symbol9'];

        } else if ( $upSymbolType == 2 && $downSymbolType == 8 ) {
            $result['symbolProperty'] = $this->lang['home_property1'];
            $result['symbolName'] = $this->lang['home_symbol10'];

        } else if ( $upSymbolType == 6 && $downSymbolType == 7 ) {
            $result['symbolProperty'] = $this->lang['home_property1'];
            $result['symbolName'] = $this->lang['home_symbol11'];

        } else if ( $upSymbolType == 4 && $downSymbolType == 7 ) {
            $result['symbolProperty'] = $this->lang['home_property1'];
            $result['symbolName'] = $this->lang['home_symbol12'];

        } else if ( $upSymbolType == 2 && $downSymbolType == 6 ) {
            $result['symbolProperty'] = $this->lang['home_property1'];
            $result['symbolName'] = $this->lang['home_symbol13'];

        } else if ( $upSymbolType == 2 && $downSymbolType == 7 ) {
            $result['symbolProperty'] = $this->lang['home_property1'];
            $result['symbolName'] = $this->lang['home_symbol14'];

        } else if ( $upSymbolType == 8 && $downSymbolType == 7 ) {
            $result['symbolProperty'] = $this->lang['home_property1'];
            $result['symbolName'] = $this->lang['home_symbol15'];

        } else if ( $upSymbolType == 4 && $downSymbolType == 2 ) {
            $result['symbolProperty'] = $this->lang['home_property1'];
            $result['symbolName'] = $this->lang['home_symbol16'];

        } else if ( $upSymbolType == 3 && $downSymbolType == 3 ) {
            $result['symbolProperty'] = $this->lang['home_property4'];
            $result['symbolName'] = $this->lang['home_symbol17'];

        } else if ( $upSymbolType == 3 && $downSymbolType == 5 ) {
            $result['symbolProperty'] = $this->lang['home_property4'];
            $result['symbolName'] = $this->lang['home_symbol18'];

        } else if ( $upSymbolType == 7 && $downSymbolType == 6 ) {
            $result['symbolProperty'] = $this->lang['home_property4'];
            $result['symbolName'] = $this->lang['home_symbol19'];

        } else if ( $upSymbolType == 1 && $downSymbolType == 6 ) {
            $result['symbolProperty'] = $this->lang['home_property4'];
            $result['symbolName'] = $this->lang['home_symbol20'];

        } else if ( $upSymbolType == 3 && $downSymbolType == 7 ) {
            $result['symbolProperty'] = $this->lang['home_property4'];
            $result['symbolName'] = $this->lang['home_symbol21'];

        } else if ( $upSymbolType == 3 && $downSymbolType == 6 ) {
            $result['symbolProperty'] = $this->lang['home_property4'];
            $result['symbolName'] = $this->lang['home_symbol22'];

        } else if ( $upSymbolType == 5 && $downSymbolType == 6 ) {
            $result['symbolProperty'] = $this->lang['home_property4'];
            $result['symbolName'] = $this->lang['home_symbol23'];

        } else if ( $upSymbolType == 1 && $downSymbolType == 3 ) {
            $result['symbolProperty'] = $this->lang['home_property4'];
            $result['symbolName'] = $this->lang['home_symbol24'];

        } else if ( $upSymbolType == 4 && $downSymbolType == 4 ) {
            $result['symbolProperty'] = $this->lang['home_property2'];
            $result['symbolName'] = $this->lang['home_symbol25'];

        } else if ( $upSymbolType == 4 && $downSymbolType == 6 ) {
            $result['symbolProperty'] = $this->lang['home_property2'];
            $result['symbolName'] = $this->lang['home_symbol26'];

        } else if ( $upSymbolType == 8 && $downSymbolType == 5 ) {
            $result['symbolProperty'] = $this->lang['home_property2'];
            $result['symbolName'] = $this->lang['home_symbol27'];

        } else if ( $upSymbolType == 2 && $downSymbolType == 5 ) {
            $result['symbolProperty'] = $this->lang['home_property2'];
            $result['symbolName'] = $this->lang['home_symbol28'];

        } else if ( $upSymbolType == 4 && $downSymbolType == 8 ) {
            $result['symbolProperty'] = $this->lang['home_property2'];
            $result['symbolName'] = $this->lang['home_symbol29'];

        } else if ( $upSymbolType == 4 && $downSymbolType == 5 ) {
            $result['symbolProperty'] = $this->lang['home_property2'];
            $result['symbolName'] = $this->lang['home_symbol30'];

        } else if ( $upSymbolType == 6 && $downSymbolType == 5 ) {
            $result['symbolProperty'] = $this->lang['home_property2'];
            $result['symbolName'] = $this->lang['home_symbol31'];

        } else if ( $upSymbolType == 2 && $downSymbolType == 4 ) {
            $result['symbolProperty'] = $this->lang['home_property2'];
            $result['symbolName'] = $this->lang['home_symbol32'];

        } else if ( $upSymbolType == 5 && $downSymbolType == 5 ) {
            $result['symbolProperty'] = $this->lang['home_property2'];
            $result['symbolName'] = $this->lang['home_symbol33'];

        } else if ( $upSymbolType == 5 && $downSymbolType == 1 ) {
            $result['symbolProperty'] = $this->lang['home_property2'];
            $result['symbolName'] = $this->lang['home_symbol34'];

        } else if ( $upSymbolType == 5 && $downSymbolType == 3 ) {
            $result['symbolProperty'] = $this->lang['home_property2'];
            $result['symbolName'] = $this->lang['home_symbol35'];

        } else if ( $upSymbolType == 1 && $downSymbolType == 4 ) {
            $result['symbolProperty'] = $this->lang['home_property2'];
            $result['symbolName'] = $this->lang['home_symbol36'];

        } else if ( $upSymbolType == 5 && $downSymbolType == 4 ) {
            $result['symbolProperty'] = $this->lang['home_property2'];
            $result['symbolName'] = $this->lang['home_symbol37'];

        } else if ( $upSymbolType == 7 && $downSymbolType == 4 ) {
            $result['symbolProperty'] = $this->lang['home_property2'];
            $result['symbolName'] = $this->lang['home_symbol38'];

        } else if ( $upSymbolType == 7 && $downSymbolType == 5 ) {
            $result['symbolProperty'] = $this->lang['home_property2'];
            $result['symbolName'] = $this->lang['home_symbol39'];

        } else if ( $upSymbolType == 3 && $downSymbolType == 4 ) {
            $result['symbolProperty'] = $this->lang['home_property2'];
            $result['symbolName'] = $this->lang['home_symbol40'];

        } else if ( $upSymbolType == 6 && $downSymbolType == 6 ) {
            $result['symbolProperty'] = $this->lang['home_property3'];
            $result['symbolName'] = $this->lang['home_symbol41'];

        } else if ( $upSymbolType == 6 && $downSymbolType == 2 ) {
            $result['symbolProperty'] = $this->lang['home_property3'];
            $result['symbolName'] = $this->lang['home_symbol42'];

        } else if ( $upSymbolType == 6 && $downSymbolType == 4 ) {
            $result['symbolProperty'] = $this->lang['home_property3'];
            $result['symbolName'] = $this->lang['home_symbol43'];

        } else if ( $upSymbolType == 6 && $downSymbolType == 3 ) {
            $result['symbolProperty'] = $this->lang['home_property3'];
            $result['symbolName'] = $this->lang['home_symbol44'];

        } else if ( $upSymbolType == 2 && $downSymbolType == 3 ) {
            $result['symbolProperty'] = $this->lang['home_property3'];
            $result['symbolName'] = $this->lang['home_symbol45'];

        } else if ( $upSymbolType == 4 && $downSymbolType == 3 ) {
            $result['symbolProperty'] = $this->lang['home_property3'];
            $result['symbolName'] = $this->lang['home_symbol46'];

        } else if ( $upSymbolType == 8 && $downSymbolType == 3 ) {
            $result['symbolProperty'] = $this->lang['home_property3'];
            $result['symbolName'] = $this->lang['home_symbol47'];


        } else if ( $upSymbolType == 8 && $downSymbolType == 6 ) {
            $result['symbolProperty'] = $this->lang['home_property3'];
            $result['symbolName'] = $this->lang['home_symbol48'];

        } else if ( $upSymbolType == 7 && $downSymbolType == 7 ) {
            $result['symbolProperty'] = $this->lang['home_property5'];
            $result['symbolName'] = $this->lang['home_symbol49'];

        } else if ( $upSymbolType == 7 && $downSymbolType == 3 ) {
            $result['symbolProperty'] = $this->lang['home_property5'];
            $result['symbolName'] = $this->lang['home_symbol50'];

        } else if ( $upSymbolType == 7 && $downSymbolType == 1 ) {
            $result['symbolProperty'] = $this->lang['home_property5'];
            $result['symbolName'] = $this->lang['home_symbol51'];

        } else if ( $upSymbolType == 7 && $downSymbolType == 2 ) {
            $result['symbolProperty'] = $this->lang['home_property5'];
            $result['symbolName'] = $this->lang['home_symbol52'];


        } else if ( $upSymbolType == 3 && $downSymbolType == 2 ) {
            $result['symbolProperty'] = $this->lang['home_property5'];
            $result['symbolName'] = $this->lang['home_symbol53'];

        } else if ( $upSymbolType == 1 && $downSymbolType == 2 ) {
            $result['symbolProperty'] = $this->lang['home_property5'];
            $result['symbolName'] = $this->lang['home_symbol54'];

        } else if ( $upSymbolType == 5 && $downSymbolType == 2 ) {
            $result['symbolProperty'] = $this->lang['home_property5'];
            $result['symbolName'] = $this->lang['home_symbol55'];

        } else if ( $upSymbolType == 5 && $downSymbolType == 7 ) {
            $result['symbolProperty'] = $this->lang['home_property5'];
            $result['symbolName'] = $this->lang['home_symbol56'];


        } else if ( $upSymbolType == 8 && $downSymbolType == 8 ) {
            $result['symbolProperty'] = $this->lang['home_property5'];
            $result['symbolName'] = $this->lang['home_symbol57'];

        } else if ( $upSymbolType == 8 && $downSymbolType == 4 ) {
            $result['symbolProperty'] = $this->lang['home_property5'];
            $result['symbolName'] = $this->lang['home_symbol58'];

        } else if ( $upSymbolType == 8 && $downSymbolType == 2 ) {
            $result['symbolProperty'] = $this->lang['home_property5'];
            $result['symbolName'] = $this->lang['home_symbol59'];

        } else if ( $upSymbolType == 8 && $downSymbolType == 1 ) {
            $result['symbolProperty'] = $this->lang['home_property5'];
            $result['symbolName'] = $this->lang['home_symbol60'];

        } else if ( $upSymbolType == 4 && $downSymbolType == 1 ) {
            $result['symbolProperty'] = $this->lang['home_property5'];
            $result['symbolName'] = $this->lang['home_symbol61'];

        } else if ( $upSymbolType == 2 && $downSymbolType == 1 ) {
            $result['symbolProperty'] = $this->lang['home_property5'];
            $result['symbolName'] = $this->lang['home_symbol62'];

        } else if ( $upSymbolType == 6 && $downSymbolType == 1 ) {
            $result['symbolProperty'] = $this->lang['home_property5'];
            $result['symbolName'] = $this->lang['home_symbol63'];

        } else {
            $result['symbolProperty'] = $this->lang['home_property5'];
            $result['symbolName'] = $this->lang['home_symbol64'];
        } // ( $upSymbolType == 6 && $downSymbolType == 8 )


        return $result;
    }

    /**
     * getSoul
     */
    private function getSoul($hour)
    {
        $hour = ( $hour != 0 ) ? $hour : 6;


        return "<div class='soul soul{$hour}'>O</div>";
    }

    /**
     * getConvertSymbol
     */
    private function getConvertSymbol($dayType, $monthType, $hourType, $event)
    {
        if ( !$hourType ) {
            return array();
        }

        $hourType = ( is_array($hourType) ) ? $hourType : array($hourType);


        $result = array();

        $dayHour = array();
        if ( in_array(4, $hourType) ) {
            $dayHour[] = 1;
        }
        if ( in_array(5, $hourType) ) {
            $dayHour[] = 2;
        }
        if ( in_array(6, $hourType) ) {
            $dayHour[] = 3;
        }
        $dayType = $this->convertType($dayType, $dayHour);
        $upSymbol = $this->getSymbol($dayType);
        $monthHour = array();
        if ( in_array(1, $hourType) ) {
            $monthHour[] = 1;
        }
        if ( in_array(2, $hourType) ) {
            $monthHour[] = 2;
        }
        if ( in_array(3, $hourType) ) {
            $monthHour[] = 3;
        }
        $monthType = $this->convertType($monthType, $monthHour);
        $downSymbol = $this->getSymbol($monthType);
        $result['symbol'] = $upSymbol . $downSymbol;

        $family = $this->getFamily($dayType, $monthType, $event['emptyType'], $event['brokenType']);
        $result['symbolName'] = $family['symbolName'];
        $result['symbolProperty'] = $family['symbolProperty'];


        return $result;
    }


    /**
     * convertType
     */
    private function convertType($type, $hour)
    {
        if ( !$hour ) {
            return $type;
        }

        $resultType = $type;
        switch ( $type ) {
            case 1:
                if ( in_array(1, $hour) && in_array(2, $hour) && in_array(3, $hour) ) {
                    $resultType = 8;
                } else if ( in_array(1, $hour) && in_array(2, $hour) ) {
                    $resultType = 7;
                } else if ( in_array(1, $hour) && in_array(3, $hour) ) {
                    $resultType = 6;
                } else if ( in_array(2, $hour) && in_array(3, $hour) ) {
                    $resultType = 4;
                } else if ( in_array(1, $hour) ) {
                    $resultType = 5;
                } else if ( in_array(2, $hour) ) {
                    $resultType = 3;
                } else if ( in_array(3, $hour) ) {
                    $resultType = 2;
                } else {
                    return false;
                }
                break;

            case 2:
                if ( in_array(1, $hour) && in_array(2, $hour) && in_array(3, $hour) ) {
                    $resultType = 7;
                } else if ( in_array(1, $hour) && in_array(2, $hour) ) {
                    $resultType = 8;
                } else if ( in_array(1, $hour) && in_array(3, $hour) ) {
                    $resultType = 5;
                } else if ( in_array(2, $hour) && in_array(3, $hour) ) {
                    $resultType = 3;
                } else if ( in_array(1, $hour) ) {
                    $resultType = 6;
                } else if ( in_array(2, $hour) ) {
                    $resultType = 4;
                } else if ( in_array(3, $hour) ) {
                    $resultType = 1;
                } else {
                    return false;
                }
                break;

            case 3:
                if ( in_array(1, $hour) && in_array(2, $hour) && in_array(3, $hour) ) {
                    $resultType = 6;
                } else if ( in_array(1, $hour) && in_array(2, $hour) ) {
                    $resultType = 5;
                } else if ( in_array(1, $hour) && in_array(3, $hour) ) {
                    $resultType = 8;
                } else if ( in_array(2, $hour) && in_array(3, $hour) ) {
                    $resultType = 2;
                } else if ( in_array(1, $hour) ) {
                    $resultType = 7;
                } else if ( in_array(2, $hour) ) {
                    $resultType = 1;
                } else if ( in_array(3, $hour) ) {
                    $resultType = 4;
                } else {
                    return false;
                }
                break;

            case 4:
                if ( in_array(1, $hour) && in_array(2, $hour) && in_array(3, $hour) ) {
                    $resultType = 5;
                } else if ( in_array(1, $hour) && in_array(2, $hour) ) {
                    $resultType = 6;
                } else if ( in_array(1, $hour) && in_array(3, $hour) ) {
                    $resultType = 7;
                } else if ( in_array(2, $hour) && in_array(3, $hour) ) {
                    $resultType = 1;
                } else if ( in_array(1, $hour) ) {
                    $resultType = 8;
                } else if ( in_array(2, $hour) ) {
                    $resultType = 2;
                } else if ( in_array(3, $hour) ) {
                    $resultType = 3;
                } else {
                    return false;
                }
                break;

            case 5:
                if ( in_array(1, $hour) && in_array(2, $hour) && in_array(3, $hour) ) {
                    $resultType = 4;
                } else if ( in_array(1, $hour) && in_array(2, $hour) ) {
                    $resultType = 3;
                } else if ( in_array(1, $hour) && in_array(3, $hour) ) {
                    $resultType = 2;
                } else if ( in_array(2, $hour) && in_array(3, $hour) ) {
                    $resultType = 8;
                } else if ( in_array(1, $hour) ) {
                    $resultType = 1;
                } else if ( in_array(2, $hour) ) {
                    $resultType = 7;
                } else if ( in_array(3, $hour) ) {
                    $resultType = 6;
                } else {
                    return false;
                }
                break;

            case 6:
                if ( in_array(1, $hour) && in_array(2, $hour) && in_array(3, $hour) ) {
                    $resultType = 3;
                } else if ( in_array(1, $hour) && in_array(2, $hour) ) {
                    $resultType = 4;
                } else if ( in_array(1, $hour) && in_array(3, $hour) ) {
                    $resultType = 1;
                } else if ( in_array(2, $hour) && in_array(3, $hour) ) {
                    $resultType = 7;
                } else if ( in_array(1, $hour) ) {
                    $resultType = 2;
                } else if ( in_array(2, $hour) ) {
                    $resultType = 8;
                } else if ( in_array(3, $hour) ) {
                    $resultType = 5;
                } else {
                    return false;
                }
                break;

            case 7:
                if ( in_array(1, $hour) && in_array(2, $hour) && in_array(3, $hour) ) {
                    $resultType = 2;
                } else if ( in_array(1, $hour) && in_array(2, $hour) ) {
                    $resultType = 1;
                } else if ( in_array(1, $hour) && in_array(3, $hour) ) {
                    $resultType = 4;
                } else if ( in_array(2, $hour) && in_array(3, $hour) ) {
                    $resultType = 6;
                } else if ( in_array(1, $hour) ) {
                    $resultType = 3;
                } else if ( in_array(2, $hour) ) {
                    $resultType = 5;
                } else if ( in_array(3, $hour) ) {
                    $resultType = 8;
                } else {
                    return false;
                }
                break;

            case 8:
            default:
                if ( in_array(1, $hour) && in_array(2, $hour) && in_array(3, $hour) ) {
                    $resultType = 1;
                } else if ( in_array(1, $hour) && in_array(2, $hour) ) {
                    $resultType = 2;
                } else if ( in_array(1, $hour) && in_array(3, $hour) ) {
                    $resultType = 3;
                } else if ( in_array(2, $hour) && in_array(3, $hour) ) {
                    $resultType = 5;
                } else if ( in_array(1, $hour) ) {
                    $resultType = 4;
                } else if ( in_array(2, $hour) ) {
                    $resultType = 6;
                } else if ( in_array(3, $hour) ) {
                    $resultType = 7;
                } else {
                    return false;
                }
                break;
        }


        return $resultType;
    }
}