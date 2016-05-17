<?php
/**
 * 公共方法文件
 * User: wanyunshan
 * Date: 2016/5/13
 * Time: 13:03
 */

/**
 * 用于展示下拉列表框
 * @param array $data  传入需要遍历的数据
 * @param $fields_name   提交的字段名称
 * @param $fields_id
 * @param $name
 */
function showSelect(array $data,$fields_name,$fields_id,$name,$id){

    $selectHtml = '<select name="' . $name . '" >';
    $selectHtml .= '<option value="">请选择...</option>';
    $selected = "";
    foreach($data as $val){
        if($val[$fields_id] == $id){
            $selected = 'selected="selected"';
        }
        $selectHtml .= '<option value="' . $val[$fields_id] . '" ' . $selected . '>' . $val[$fields_name] . '</option>';
    }
    $selectHtml .= '</select>';
    return $selectHtml;

}

/**
 * @param array $data
 * @param $name
 * @param $id
 * @return string
 */
function showStatusAndSale(array $data,$name,$id){

    $selectHtml = '<select name="' . $name . '" >';
    $selectHtml .= '<option value="">请选择...</option>';
    $selected = "";
    foreach($data as $key => $val){
        if($key === $id){
            $selected = 'selected="selected"';
        }
        $selectHtml .= '<option value="' . $key . '" ' . $selected . '>' . $val . '</option>';
    }
    $selectHtml .= '</select>';
    return $selectHtml;

}