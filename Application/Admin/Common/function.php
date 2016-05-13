<?php
/**
 * 公共方法文件
 * User: wanyunshan
 * Date: 2016/5/13
 * Time: 13:03
 */

/**
 * @param array $data  传入需要遍历的数据
 * @param $fields_name   提交的字段名称
 * @param $fields_id
 * @param $name
 */
function showSelect(array $data,$fields_name,$fields_id){

    $selectHtml = '<select name="' . $fields_id . '"';
    foreach($data as $val){
        $selectHtml .= '<option value="' . $val[$fields_id] . '">' . $val[$fields_name] . '</option>';
    }
    $selectHtml .= '</select>';


}