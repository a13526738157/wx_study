<?php
/**
 * 空拖模型
 * Created by Whb
 * Author: Whb
 * Date: 2016/11/28
 * Time: 17:38
 */

namespace Common\Model;
use Think\Model;

class Custom1Model extends Model
{
    protected $tableName = 'custom_1';
    public function getData($row){


        $pcategory = $row['pcategory'];
        $pcategory_arr = array_unique(array_filter(explode(',',$pcategory)));
        if($pcategory_arr){
            $newarr[] = $pcategory_arr;
        }

        $serie = $row['serie'];
        $serie_arr = array_unique(array_filter(explode(',',$serie)));
        if($serie_arr){
            $newarr[] = $serie_arr;
        }

        $aoption = ' stock>0 ';
        if($row['pcategory_all']){
            $pcategory_str = join(',',$row['pcategory_all']);
            $pcategory_str = "'".str_replace(",","','",$pcategory_str)."'";
            $aoption .= " and pcategory in ($pcategory_str)";
        }
        if($row['serie_all']){
            $serie_str = implode(',',$row['serie_all']);
            $serie_str = "'".str_replace(",","','",$serie_str)."'";
            $aoption .= " and serie in ($serie_str)";
        }
        $gold = $row['gold'];
        $gold_arr = array_unique(array_filter(explode('-',$gold)));
        if($gold_arr){
            if($gold_arr[0]){
                $aoption .= " and mweight >='{$gold_arr[0]}'";
            }
            if($gold_arr[1]){
                $aoption .= " and mweight <='{$gold_arr[1]}'";
            }
            // $condition.=" or (price >= '{$price_arr[0]}' $price_option) ";
        }
        $new = getArrSet($newarr);
        foreach($new as $v){
            if($aoption){
                $con.= " or (".$aoption." and ";
            } else {
                $con.= " or (";
            }
            foreach($v as $kk=>$vv){
                if($pcategory_arr){
                    if($kk == 0){
                        $con.= " pcategory='{$vv}' ";
                    } elseif($kk == 1){
                        $con.= " and serie='{$vv}' ";
                    }
                } elseif(!$pcategory_arr){
                    $con.= "serie='{$vv}' ";
                }
                
            }
            // $con = substr($con,2);
            $con.= ")";
            // $condition="";
        }
        if($new){
            $condition = substr($con,3);
        } else {
            $condition = $aoption;
        }

        if($condition){
            $where['_string'] = $condition;
        }
        if($row['length']){
            $this->limit($row['length']);
        }
        if($row['page']){
            $this->page($row['page']);
        }
        // $where['stock'] = array('gt',0);
        $list = $this->field('style,picUrl1,picUrl2,mweight,au750,pt950,pd950')
            ->where($where)
            //->group('style')

            ->select();//echo $this->getLastsql();die;
        return $list;
    }
    public function chageStatus($status,$old_status,$did)
    {
        return $this->where('style="'.$did.'" and status='.$old_status)->setField(array('status'=>$status));
    }
}