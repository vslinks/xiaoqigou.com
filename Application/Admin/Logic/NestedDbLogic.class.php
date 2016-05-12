<?php
/**
 * Created by PhpStorm.
 * User: wanyunshan
 * Date: 2016/5/12
 * Time: 17:09
 */

namespace Admin\Logic;


use Admin\Logic\DbMysql;

/**
 * Class NestedBdLogic
 * @package MediaCore\Lib\Db\connectors
 */
class NestedDbLogic implements DbMysql
{


    public function getCol($sql, array $args = array())
    {
        echo __METHOD__ . "</br>";
        dump(func_get_args());
        echo "</hr>";
        // TODO: Implement getCol() method.
    }

    public function connect()
    {
        echo __METHOD__ . "</br>";
        dump(func_get_args());
        echo "</hr>";
        // TODO: Implement connect() method.
    }

    public function disconnect()
    {
        echo __METHOD__ . "</br>";
        dump(func_get_args());
        echo "</hr>";
        // TODO: Implement disconnect() method.
    }

    public function free($result)
    {
        echo __METHOD__ . "</br>";
        dump(func_get_args());
        echo "</hr>";
        // TODO: Implement free() method.
    }



    public function update($sql, array $args = array())
    {
        echo __METHOD__ . "</br>";
        dump(func_get_args());
        echo "</hr>";
        // TODO: Implement update() method.
    }

    public function getAll($sql, array $args = array())
    {
        echo __METHOD__ . "</br>";
        dump(func_get_args());
        echo "</hr>";
        // TODO: Implement getAll() method.
    }

    public function getAssoc($sql, array $args = array())
    {
        echo __METHOD__ . "</br>";
        dump(func_get_args());
        echo "</hr>";
        // TODO: Implement getAssoc() method.
    }
    public function getOne($sql, array $args = array())
    {
        /*echo __METHOD__ . "</br>";
        dump(func_get_args());
        echo "</hr>";*/

        $param = func_get_args();//>> 接收所有参数
        $sql = array_shift($param);
//        dump($param);
        $sql = str_replace('?F',$param[0],$sql);
        $sql = str_replace('?T',$param[1],$sql);
        $max = array_shift(M()->query($sql));
        return array_shift($max);
        // TODO: Implement getOne() method.
    }


    public function getRow($sql, array $args = array())
    {
//        echo __METHOD__ . "</br>";
//        dump(func_get_args());
//        echo "</hr>";

        //>> 首先用一个变量来接收所有的参数
        $param = func_get_args();
        $sql = array_shift($param);//>>取出条一个元素 sql语句结构
        //>> 用正则表达式把sql语句拆分成一个数组.
        $sqls = preg_split('/\?[TFN]/ ',$sql);
//        dump($sqls);
        $map = array();
        foreach($sqls as $key => $val){
            $map[] = $val . $param[$key];
        }
        //>> 拼接出sql语句
        $sql = implode('',$map);
        //>> 调用基础模型中的query方法执行sql语句.
        $rows = M()->query($sql);
        return array_shift($rows);
        // TODO: Implement getRow() method.
    }



    public function query($sql, array $args = array())
    {
      /*  echo __METHOD__ . "</br>";
        dump(func_get_args());
        echo "</hr>";*/

        $param = func_get_args();
        $sql = array_shift($param);//>>取出条一个元素 sql语句结构
        //>> 用正则表达式把sql语句拆分成一个数组.
        $sqls = preg_split('/\?[TFN]/ ',$sql);
        $map = array();
        foreach($sqls as $key => $val){
            $map[] = $val . $param[$key];
        }
        //>> 拼接出sql语句
        $sql = implode('',$map);
        //>> 调用基础模型中的execute方法执行sql语句.
        $result = M()->execute($sql);
        if($result === false){
            return false;
        }
        return (M()->getLastInsID());

        // TODO: Implement query() method.
    }

    public function insert($sql, array $args = array())
    {/*
        echo __METHOD__ . "</br>";
        dump(func_get_args());
        echo "</hr>";*/
        $param = func_get_args();//>>接收所有参数
        $sql = array_shift($param);//取得sql 语句结构
        $first = array_shift($param); //>.取出第二个参数
        $second  = array_shift($param); //>>取得第三个参数
        unset($second['id']);  //>>删除id
        $sql = str_replace('?T','`'.$first.'`',$sql); //>>把第?T替换掉
        $map = array();//>>用一个空数组来拼装sql语句
        foreach ($second as $key => $val){
            $map[] = "`$key`" .'=' . "'$val'";
        }
        //>>拼接sql语句
        $sql = str_replace('?%',implode(',',$map),$sql);
        //>>调用方法执行sql 语句
        $result = M()->execute($sql);
        return $result;
        // TODO: Implement insert() method.
    }


}