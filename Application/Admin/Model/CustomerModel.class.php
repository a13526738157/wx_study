<?php
/**
 * @Author: Sincez
 * @Date:   2016-11-02 22:41:25
 * @Last Modified by:   Sincez
 * @Last Modified time: 2016-11-03 15:03:47
 */
namespace Admin\Model;
use Think\Model;
class CustomerModel extends Model
{
	protected $tableName = 'customer_service';

	protected $col = 'uid,code,name,mobile,tid,is_sms,status';

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
		$data = $this
			->select();
		return $data;
	}
}