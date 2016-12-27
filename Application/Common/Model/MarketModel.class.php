<?php
/**
 * @Author: Sincez
 * @Date:   2016-10-30 13:15:48
 * @Last Modified by:   Sincez
 * @Last Modified time: 2016-10-30 18:48:22
 */
namespace Common\Model;
use Think\Model;
class MarketModel extends Model
{
	protected $tableName = 'market_info';
	protected $_validate = array();
    protected $_auto = array ();

	public function insertData($data){
		if($this->create($data)){
			if(isset($data['id'])){
				$id = $this->save();
			}else{
				$id = $this->add();
			}
		}else{
			return $this->getError();
		}
	}
}