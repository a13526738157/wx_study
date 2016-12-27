<?php
/**
 * @Author: Sincez
 * @Date:   2016-11-18 14:49:09
 * @Last Modified by:   Sincez
 * @Last Modified time: 2016-11-19 15:49:40
 */
namespace Admin\Model;
use Think\Model;
class GoodstypeModel extends Model
{
	protected $tableName = 'goodstype';

	protected $col = 'bid,name,is_show,ord,uid,brand_id';

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
			$this->where(array('brand_id'=>$row['brand_id']));
		}
		$data = $this->order('if(ord=0,99999,ord)')->select();
		return $data;
	}
}