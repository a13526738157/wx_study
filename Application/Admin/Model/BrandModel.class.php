<?php
/**
 * @Author: Sincez
 * @Date:   2016-10-30 13:15:48
 * @Last Modified by:   Sincez
 * @Last Modified time: 2016-11-22 18:27:45
 */
namespace Admin\Model;
use Think\Model;
class BrandModel extends Model
{
	protected $tableName = 'brand';

	protected $col = 'uid,brand_code,full_name,brand_name,fz_man,tel,email,sheng,city,area,address,logo,site,is_lawer,status,sh_sheng,sh_city,sh_area,sh_address,sh_man,sh_tel,adduser,business,theme,desc,media,com_desc,dream,core_value,theme,produce_desc';

	public function insertData($row){
		$data = array_bykeys($row,$this->col);
		if($row['id']){
			$this->where(array('id'=>$row['id']))->save($data);
			$brand_id = $row['id'];
		} else {
			$brand_id = $this->add($data);
		}
		return $brand_id;
	}

	public function getData($row){
		if($row['status']>-1){
			$this->where(array('sj_brand.status'=>$row['status']));
		}
		$data = $this->field('sj_brand.*,sj_users.m_code')
					->join('sj_users on sj_brand.uid=sj_users.uid','left')
					->select();
		return $data;
	}
}