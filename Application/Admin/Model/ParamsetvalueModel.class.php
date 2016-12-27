<?php
/**
 * @Author: Sincez
 * @Date:   2016-11-18 17:30:50
 * @Last Modified by:   Sincez
 * @Last Modified time: 2016-12-05 00:21:21
 */
namespace Admin\Model;
use Think\Model;
class ParamsetvalueModel extends Model
{
	protected $tableName = 'paramset_detail';

	protected $col = 'paramset_id,name,value,is_show,ord,brand_id';

	public function insertData($row){
		$data = array_bykeys($row,$this->col);
		if($row['id']){
			$this->where(array('id'=>$row['id']))->save($data);
		} else {
			$this->add($data);
		}
	}

	public function getData($row){
		if($row['brand_id']){
			$this->where(array('sj_paramset_detail.brand_id'=>$row['brand_id']));
		}
		if($row['paramset_id']){
			$this->where(array('paramset_id'=>$row['paramset_id']));
		}
		$data = $this->order('if(ord=0,99999,ord)')->select();
		return $data;
	}

	public function getResult($row){
		if($row['brand_id']){
			$this->where(array('sj_paramset_detail.brand_id'=>$row['brand_id']));
		}
		if(isset($row['is_search'])){
			$this->where(array('is_search'=>$row['is_search']));
		}
		if(isset($row['is_sale'])){
			$this->where(array('is_sale'=>$row['is_sale']));
		}
		if(isset($row['is_sale_percent'])){
			$this->where(array('is_sale_percent'=>$row['is_sale_percent']));
		}
		if($row['cids']){
			$this->where(array('sj_paramset_detail.id'=>array('in',$row['cids'])));
		}
		$data = $this->field('sj_paramset_detail.*,sj_paramset.name as paramset_name,sj_paramset.is_weight,sj_goodstype.name as goodstype_name,sj_paramset.goodstype_id')
			->join('sj_paramset on sj_paramset_detail.paramset_id=sj_paramset.id','left')
			->join('sj_goodstype on sj_paramset.goodstype_id=sj_goodstype.id','left')
			->select();
		return $data;
	}

	public function getSearchOption($row){
		if($row['goodstype_id']){
			$this->where(array('goodstype_id'=>$row['goodstype_id']));
		}
		if($row['brand_id']){
			$this->where(array('sj_paramset_detail.brand_id'=>$row['brand_id']));
		}
		if($row['search_type']){
			if(is_array($row['search_type'])){
				$this->where(array('search_type'=>array('in',$row['search_type'])));
			} else {
				$this->where(array('search_type'=>$row['search_type']));
			}
		}
		if($row['is_search']){
			$this->where(array('is_search'=>$row['is_search']));
		}
		if($row['detail_ids']){
			$this->where(array('sj_paramset_detail.id'=>array('in',$row['detail_ids'])));
		}
		if($row['paramset_ids']){
			$this->where(array('sj_paramset_detail.paramset_id'=>array('in',$row['paramset_ids'])));
		}
		$data = $this->field('sj_paramset_detail.*,sj_paramset.name as paramset_name,sj_paramset.is_weight,col_name,search_type')
			->join('sj_paramset on sj_paramset_detail.paramset_id=sj_paramset.id','left')
			->select();//echo $this->getLastsql();die;
		foreach($data as $v){
			$result[$v['paramset_id']]['name'] = $v['paramset_name'];
			$result[$v['paramset_id']]['col_name'] = $v['col_name'];
			$result[$v['paramset_id']]['search_type'] = $v['search_type'];
			$result[$v['paramset_id']]['tree'][$v['id']]['detail_id'] = $v['id'];
			$result[$v['paramset_id']]['tree'][$v['id']]['name'] = $v['name'];
			$result[$v['paramset_id']]['tree'][$v['id']]['value'] = $v['value'];
		}
		return $result;
	}
}