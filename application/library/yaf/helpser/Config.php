<?php

/**
 * 配置管理类
 * User: moonbire
 * Date: 2016/8/19
 * Time: 16:04
 */

namespace yaf\helpser;

class Config
{
    public static function get($key){

        $config =  \Yaf_Registry::get('config')->get($key);

        if(null == $config){

            return false;
        } else {

            if (is_object($config)){

                return $config->toArray();
            } else {

                return $config;
            }
        }
    }
}