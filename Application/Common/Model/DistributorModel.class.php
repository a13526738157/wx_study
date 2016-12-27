<?php
/**
 * @Author: Sincez
 * @Date:   2016-10-30 13:15:48
 * @Last Modified by:   Sincez
 * @Last Modified time: 2016-11-08 15:58:35
 */
namespace Common\Model;
use Think\Model;
class DistributorModel extends Model
{
	protected $tableName = 'distributor';
	protected $_validate = array();
    protected $_auto = array ();

	public function insertData($data)
	{
		if(isset($data['id'])){
			unset($data['fx_user']);
		}
		//查找有没有该商户的
		if($this->create($data)){
			if(isset($data['id'])){
				$id = $this->save();
			}else{
				$id = $this->add();
			}
			return $id;
		}else{
			return false;
		}
	}
	public function getData($where,$order='regtime desc')
	{
		$this->alias('a');
		$this->join('__USERS__ as u on u.uid=a.uid','left');
		$this->where($where);
		$this->order($order);
		return $this->select();
	}
	/**
	*@Author: 	Whb
	*@param 	int $uid 用户id
	*@return    array or boolean
	*
	*/
	public function getBrands($uid)
	{
		return $this->alias('a')->field('b.brand_name,b.id,b.logo')
		->join('__BRAND__ as b on b.id=a.pid','left')
		->where(array('a.uid'=>$uid))->select();
	}
}