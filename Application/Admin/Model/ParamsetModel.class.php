<?php
/**
 * @Author: Sincez
 * @Date:   2016-11-18 16:33:12
 * @Last Modified by:   Sincez
 * @Last Modified time: 2016-11-23 12:18:59
 */
namespace Admin\Model;
use Think\Model;
class ParamsetModel extends Model
{
	protected $tableName = 'paramset';

	protected $col = 'is_search,is_sale,is_sale_percent,is_show_option,goodstype_id,search_type,is_weight,name,value,is_show,brand_id,ord,uid';

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
			$this->where(array('sj_paramset.brand_id'=>$row['brand_id']));
		}
		if($row['is_show_option']){
			$this->where(array('sj_paramset.is_show_option'=>1));
		}
		$data = $this->field('sj_paramset.*,sj_goodstype.name as goodstype_name')
			->join('sj_goodstype on sj_paramset.goodstype_id=sj_goodstype.id','left')
			->order('if(sj_paramset.ord=0,99999,sj_paramset.ord)')->select();
		return $data;
	}

	public function getSearchOption($row){
		if($row['brand_id']){
			$this->where(array('brand_id'=>$row['brand_id']));
		}
		if($row['goodstype_id']){
			$this->where(array('goodstype_id'=>$row['goodstype_id']));
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
		$data = $this->order('if(sj_paramset.ord=0,99999,sj_paramset.ord)')->select();
		return $data;
	}
}