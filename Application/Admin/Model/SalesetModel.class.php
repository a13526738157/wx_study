<?php
/**
 * @Author: Sincez
 * @Date:   2016-11-07 18:00:49
 * @Last Modified by:   Sincez
 * @Last Modified time: 2016-12-07 15:13:07
 */
namespace Admin\Model;
use Think\Model;
class SalesetModel extends Model
{
	protected $tableName = 'sale_set';

	protected $col = 'brand_id,cid,uid,percent,is_default';

	public function insertData($row){
		$data = array_bykeys($row,$this->col);
		if($row['id']){
			$this->where(array('id'=>$row['id']))->save($data);
		} else {
			$this->add($data);
		}
		// $this->where(array('uid'=>$row['uid']))->delete();
		// $data['uid'] = $row['uid'];
		// foreach($row['option'] as $k=>$v){
		// 	$data['cid'] = $k;
		// 	$data['percent'] = $v['percent'];
		// 	$this->add($data);
		// }
	}

	public function getData($row){
		if($row['brand_id']){
			$this->where(array('sj_sale_set.brand_id'=>$row['brand_id']));
		}
		if($row['uid']){
			$this->where(array('sj_sale_set.uid'=>$row['uid']));
		}
		$data = $this->field('sj_sale_set.*,concat(sj_goodstype.name,"-",sj_paramset.name,"【",sj_paramset_detail.name,if(is_weight,"-",""),if(is_weight,sj_paramset_detail.value,""),"】") as param_name')
			->join('sj_paramset_detail on sj_paramset_detail.id=sj_sale_set.cid','left')
			->join('sj_paramset on sj_paramset_detail.paramset_id=sj_paramset.id','left')
			->join('sj_goodstype on sj_paramset.goodstype_id=sj_goodstype.id','left')
			->select();
		return $data;
	}

	public function getOneData($row){
		if($row['brand_id']){
			$this->where(array('sj_sale_set.brand_id'=>$row['brand_id']));
		}
		if($row['uid']){
			$this->where(array('sj_sale_set.uid'=>$row['uid']));
		}
		if($row['sale_set_id']){
			$this->where(array('sj_sale_set.id'=>$row['sale_set_id']));
		}
		$data = $this->field('sj_sale_set.*,concat(sj_goodstype.name,"-",sj_paramset.name,"【",sj_paramset_detail.name,if(is_weight,"-",""),if(is_weight,sj_paramset_detail.value,""),"】") as param_name,sj_paramset.goodstype_id')
			->join('sj_paramset_detail on sj_paramset_detail.id=sj_sale_set.cid','left')
			->join('sj_paramset on sj_paramset_detail.paramset_id=sj_paramset.id','left')
			->join('sj_goodstype on sj_paramset.goodstype_id=sj_goodstype.id','left')
			->find();
		return $data;
	}

	public function upDefault($row){
		if($row['goodstype_id']){
			$condition.= " and sj_paramset.goodstype_id='{$row['goodstype_id']}'";
		}
		if($row['uid']){
			$condition.= " and sj_sale_set.uid='{$row['uid']}'";
		}
		$condition = substr($condition,4);
		if($condition){
			$condition = 'where '.$condition;
		}
		$sql = "UPDATE `sj_sale_set` left JOIN sj_paramset_detail on sj_paramset_detail.id=sj_sale_set.cid left JOIN sj_paramset on sj_paramset_detail.paramset_id=sj_paramset.id left JOIN sj_goodstype on sj_paramset.goodstype_id=sj_goodstype.id set is_default=0 $condition";
		$this->query($sql);
	}

	public function getPercent($row){
		if(isset($row['is_default'])){
			$this->where(array('is_default'=>$row['is_default']));
		}
		if($row['brand_id']){
			$this->where(array('sj_sale_set.brand_id'=>$row['brand_id']));
		}
		if($row['uid']){
			$this->where(array('sj_sale_set.uid'=>$row['uid']));
		}
		if($row['goodstype_id']){
			$this->where(array('sj_paramset.goodstype_id'=>$row['goodstype_id']));
		}
		$info = $this->field('sj_sale_set.*')
		->join('sj_paramset_detail on sj_sale_set.cid=sj_paramset_detail.id','left')
		->join('sj_paramset on sj_paramset.id=sj_paramset_detail.paramset_id','left')
		->find();

		return $info['percent']?$info['percent']:1;
	}

	

}