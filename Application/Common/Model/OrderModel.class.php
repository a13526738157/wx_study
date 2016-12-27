<?php
/**
 * Created by Whb
 * Author: Whb
 * Date: 2016/11/22
 * Time: 16:47
 */

namespace Common\Model;
use Think\Model;


class OrderModel extends Model
{
    protected $tableName = 'Order';

    protected $col = 'id,order_no,f_no,fid,pid,uid,isquick,type,number,allnumber,price,aprice,dprice,allprice,inch,ktype,dtype,kz,isson,consigneer,create_time,pay_time,return_time,del_time,status,del,style,pay';

    public function insertData($row){

        $data = array_bykeys($row,$this->col);

        if($row['id']){
           return $this->where(array('id'=>$row['id']))->save($data);
        } else {
           return $this->add($data);
        }
    }

    public function getData($row){
        if($row['status']>-1){
            $this->where(array('status'=>$row['status']));
        }
        return $this->select();
    }
    public function getExcelRow(){
            $brand_id = session('userinfo.brand_id');//品牌商id
            $uid = session('userinfo.uid');
            $groupid = session('userinfo.groupid');
            $where1['brand_id'] = $brand_id;
            if($groupid==3){
                $where1['adduser'] = $uid;
                $where1['uid'] = $uid;
                $where1['_logic'] = 'or';
            }
            if($groupid==4)
            {
                $where1['uid'] = $uid;
            }

            switch($groupid){
                case 1:
                    break;
                case 2:
                    $sales = D('Sales')->where($where1)->getField('uid',true);
                    unset($where1['brand_id']);
                    $where1['pid'] = $brand_id;
                    $sales2 = D('Distributor')->where($where1)->getField('uid',true);
                    $sales =array_merge($sales2,$sales);
                    $map['a.uid'] = array('in',$sales);
                    break;
                case 3:
                    $sales = D('Sales')->where($where1)->getField('uid',true);
                    $map['a.uid'] = array('in',$sales);
                    break;
                case 4:
                    $map['a.uid'] = session('userinfo.uid');
                    break;
            }
            $map['a.del'] = 0;
            if(I('order_no'))
            {
                $map['order_no'] = I('order_no');
            }
            if(I('order_type',-1)>-1)
            {
                $map['a.type'] = I('order_type');
            }
            if(I('order_sale'))
            {
                #查询用户
                $map['a.uid'] = D('Sales')->where(array('code'=>I('order_sale'),'brand_id'=>$brand_id))->getField('uid');
            }
            if(I("order_status")>-1)
            {
                $map['status'] = I('order_status');
            }
            $map['a.create_time'] = array(array('egt',I('order_start')),array('elt',I('order_end')));
            $map['a.isson'] = 0;
            $lists = $this
                ->field('a.id,a.uid')
                ->alias('a')
                ->where($map)
                ->order('a.id desc')
                ->select();
            foreach ($lists as $k => $v) {
                $data = array();
                $fids[] = $v['id'];
                $uids[] = $v['uid'];
            }
            $cp_percent = D('Admin/Saleset')
            ->getPercent(array('is_default'=>1,'brand_id'=>$brand_id,'uid'=>session('userinfo.uid'),'goodstype_id'=>1));
            //裸钻价格系数
            $lz_percent = D('Admin/Saleset')
            ->getPercent(array('is_default'=>1,'brand_id'=>$brand_id,'uid'=>session('userinfo.uid'),'goodstype_id'=>3));
            //空托价格系数
            $kt_percent = D("Admin/Saleset")->getPercent(array('is_default'=>1,'brand_id'=>$brand_id,'uid'=>session('userinfo.uid'),'goodstype_id'=>2));
            //TODO:by whb 现在单纯的查成品后期需要改为各种 建议for join数组
            $temp_where = array(
                'a.uid'   =>  array('in',$uids),
                'a.fid'   =>  array('in',$fids)
                );
            $lists = $this
            ->field('a.id,s.code ucode,s.name,p.plocation,p.style,p.pname,p.mweight,p.mnumber,p.inch,p.price,p.code,p.pcertificate')
            ->alias('a')
            ->join('__SALES__ as s on a.uid=s.uid','left')
            ->join('__PRODUCT___1 as p on p.pid=a.pid','left')
            ->join('__DISTRIBUTOR__ as d on d.uid=a.uid','left')
            ->order('a.id desc')
            ->where($temp_where)
            ->select();
            foreach ($lists as $k => $v) {
                $lists[$k]['price'] = number_format($v['price'],2);
                $lists[$k]['cur_price'] = number_format($v['price']*$cp_percent,2);
            }
            return $lists;
    }
    public function exportExcel($titlename,$data,$filename){
        exportExcel($titlename,$data,$filename);
    }   
}