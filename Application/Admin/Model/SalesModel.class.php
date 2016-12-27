<?php
/**
 * @Author: Sincez
 * @Date:   2016-11-03 17:40:37
 * @Last Modified by:   Sincez
 * @Last Modified time: 2016-12-03 09:44:46
 */
namespace Admin\Model;
use Think\Model;
class SalesModel extends Model
{
	protected $tableName = 'sales';

	protected $col = 'uid,code,name,idcard,mobile,status,is_sale,brand_id,adduser';

	public function insertData($row){
		$data = array_bykeys($row,$this->col);
		if($row['id']){
			$id = $row['id'];
			$this->where(array('id'=>$row['id']))->save($data);
		} else {
			$id = $this->add($data);
		}
		return $id;
	}

	public function getData($row){
		if($row['status']>-1){
			$this->where(array('sj_sales.status'=>$row['status']));
		}
		if($row['from']){
			$this->where(array('from'=>$row['from']));
		}
		return $this->field('sj_sales.*,sj_users.nicename,sj_users.mobile')
					->join('sj_users on sj_users.uid=sj_sales.uid','left')
					->select();
	}
	public function getBrands($uid){
		return $this->alias('a')->field('b.brand_name,b.id,b.logo')
		->join('__BRAND__ as b on b.id=a.brand_id','left')
		->where(array('a.uid'=>$uid))->select();
	}	
}