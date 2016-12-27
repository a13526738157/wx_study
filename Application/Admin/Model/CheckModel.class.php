<?php
/**
 * @Author: Sincez
 * @Date:   2016-11-08 14:31:31
 * @Last Modified by:   Sincez
 * @Last Modified time: 2016-12-06 19:29:15
 */
namespace Admin\Model;
use Think\Model;
class CheckModel extends Model
{
	protected $tableName = 'check';

	protected $col = 'uid,table,content,dcontent,primary_col,primary_val,other,status';

	public function insertData($row){
		$data = array_bykeys($row,$this->col);
		if($row['id']){
			$this->where(array('id'=>$row['id']))->save($data);
		} else {
			$this->add($data);
		}
	}

	public function getData($row){
		if($row['from']){
			$where['sj_users.from'] = $row['from'];
		}
		if($row['other']){
			$where['sj_check.other'] = array('in',$row['other']);
		}
		if($row['other_one']){
			$where['sj_check.other'] = $row['other_one'];
		}
		if(isset($row['status']) && $row['status']>-1){
			$where['sj_check.status'] = $row['status'];
		}
		$data =$this->field('sj_check.*,sj_users.username,sj_users.nicename')
			->join('sj_users on sj_check.uid=sj_users.uid','left')
			->where($where)
			->select();
		return $data;
	}

	public function getOne($row){
		if($row['from']){
			$where['sj_users.from'] = $row['from'];
		}
		if($row['id']){
			$where['sj_check.id'] = $row['id'];
		}
		if($row['other']){
			$where['sj_check.other'] = $row['other'];
		}
		if($row['status_not_in']){
			$where['sj_check.status'] = array('not in ',$row['status_not_in']);
		}
		$data =$this->field('sj_check.*,sj_users.username,sj_users.nicename')
			->join('sj_users on sj_check.uid=sj_users.uid','left')
			->where($where)
			->find();
		return $data;
	}

	public function upTarget($table,$id,$upinfo){
		foreach($upinfo as $k=>$v){
			$upset .= " {$k}='{$v}',";
		}
		$upset = trim($upset,',');
		$sql = "update {$table} set $upset where id={$id}";
		// echo $sql;die;
		$this->query($sql);
	}
}