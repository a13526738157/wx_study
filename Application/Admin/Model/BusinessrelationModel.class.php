<?php
/**
 * @Author: Sincez
 * @Date:   2016-11-07 10:44:21
 * @Last Modified by:   Sincez
 * @Last Modified time: 2016-12-03 16:02:45
 */
namespace Admin\Model;
use Think\Model;
class BusinessrelationModel extends Model
{
	protected $tableName = 'business_relation';

	public function insertData($row){
		$this->where(array('uid'=>$row['uid']))->delete();
		foreach($row['business'] as $v){
			$this->add(array('uid'=>$row['uid'],'pid'=>$row['pid'],'cid'=>$v));
		}
	}

	public function getSelfBusiness($row){
		if($row['brand_id']){
			$this->where(array('pid'=>$row['brand_id']));
		}
		if($row['uid']){
			$this->where(array('uid'=>$row['uid']));
		}
		$data = $this->select();
		return array_coltoarray($data,'cid');
	}

	public function getSelesOption($row){
		if($row['uid']){
			$this->where(array('sj_business_relation.uid'=>$row['uid']));
		}
		if($row['brand_id']){
			$this->where(array('sj_business_relation.pid'=>$row['brand_id']));
		}
		if($row['goodstype_id']){
			$this->where(array('sj_paramset.goodstype_id'=>$row['goodstype_id']));
		}
		$data = $this->field('sj_business_relation.*,col_name,sj_paramset_detail.value')
			->join('sj_paramset_detail on sj_business_relation.cid=sj_paramset_detail.id','left')
			->join('sj_paramset on sj_paramset_detail.paramset_id=sj_paramset.id','left')
			->where(array('sj_paramset_detail.paramset_id'=>array('gt',0)))->select();
		foreach($data as $v){
			$newdata[$v['col_name']][] = $v['value'];
		}

		return $newdata;
		
		// echo $this->getLastsql();die;
		// print_r($data);die;


	}
}