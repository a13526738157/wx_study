<?php
/**
 * @Author: Sincez
 * @Date:   2016-11-19 21:28:59
 * @Last Modified by:   Sincez
 * @Last Modified time: 2016-12-05 11:00:38
 */
namespace Admin\Model;
use Think\Model;
class GoodstyperelationModel extends Model
{
	protected $tableName = 'goodstype_relation';

	protected $col = 'goodstype_id,paramset_id,is_show,uid,brand_id';

	public function insertData($row){
		$data = array_bykeys($row,$this->col);
		$this->add($data);
	}

	public function getCurList($row){
		if($row['uid']){
			$this->where(array('sj_goodstype_relation.uid'=>$row['uid']));
		}
		if($row['brand_id']){
			$this->where(array('sj_goodstype_relation.brand_id'=>$row['brand_id']));
		}
		if(isset($row['is_show'])){
			$this->where(array('sj_goodstype_relation.is_show'=>$row['is_show']));
		}
		if($row['show_goodstype']){
			$this->where(array('sj_goodstype_relation.goodstype_id'=>array('neq',0)));
		}
		if($row['url']){
			$this->where(array('sj_goodstype.turl'=>$row['url']));
		}
		$data = $this->field('sj_goodstype_relation.*,sj_goodstype.name,turl')
			->join('__GOODSTYPE__ on __GOODSTYPE_RELATION__.goodstype_id=__GOODSTYPE__.id','left')
			->select();
		return $data;
	}

	public function isNavShow($row){
		$list = $this->getCurList($row);
		$one = $list[0];
		if($one['is_show']){
			return true;
		} else {
			return false;
		}
	}

	public function getSaleset($row){
		if($row['uid']){
			$this->where(array('sj_goodstype_relation.uid'=>$row['uid']));
		}
		if($row['goodstype_id']){
			$this->where(array('sj_goodstype.id'=>$row['goodstype_id']));
		}
		if($row['brand_id']){
			$this->where(array('sj_goodstype_relation.brand_id'=>$row['brand_id']));
		}
		if(isset($row['is_show'])){
			$this->where(array('sj_goodstype_relation.is_show'=>$row['is_show']));
		}
		if($row['turl']){
			$this->where(array('sj_goodstype_relation.turl'=>$row['turl']));
		}
		return $this->field('sj_goodstype_relation.paramset_id')
			->join('sj_paramset on sj_paramset.id=sj_goodstype_relation.paramset_id','left')
			// ->join('sj_paramset on sj_paramset_detail.paramset_id=sj_paramset.id','left')
			->join('sj_goodstype on sj_paramset.goodstype_id=sj_goodstype.id','left')
			->where(array('sj_goodstype_relation.paramset_id'=>array('neq',0)))->select();

	}

	
}