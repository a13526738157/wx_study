<?php
/**
 * @Author: Sincez
 * @Date:   2016-11-02 22:42:25
 * @Last Modified by:   Sincez
 * @Last Modified time: 2016-11-03 10:17:48
 */
namespace Admin\Model;
use Think\Model;
class CustomertypeModel extends Model
{
	protected $tableName = 'customer_service_type';

	protected $col = 'uid,name,ord';

	public function insertData($row){
		$data = array_bykeys($row,$this->col);
		if($row['id']){
			$this->where(array('id'=>$row['id']))->save($data);
		} else {
			$this->add($data);
		}
	}

	public function getData($row){
		if($row['uid']){
			$this->where(array('uid'=>$row['uid']));
		}
		$data = $this->order('ord asc')->select();
		return $data;
	}

}