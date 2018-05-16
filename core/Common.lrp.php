<?php

namespace LunarPHP\Core;

class Common
{
    /**
     * 模糊查询
     * 
     * @param mixed $keywords 关键词
     * @param array $data 数据
     * 
     * @return array
     */
    public function arr_blurry_query($keywords, $data)
    {
        $arr = [];

        if (is_array($keywords)) {
            foreach ( $data as $key => $values ) {
                foreach ($keywords as $item) {
                    if ( (strstr( $values['name'] , $item ) !== false) && (strstr( $values['name'] , $item ) !== false) ) 
                        $arr = $values;
                }    
            }
        } else {
            foreach ( $data as $key => $values ) {
                if ( strstr( $values['name'] , $keywords ) !== false ) 
                    $arr = $values;
            }
        }

        return $arr;
    }
}