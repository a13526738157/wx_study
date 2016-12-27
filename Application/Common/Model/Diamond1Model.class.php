<?php
/**
 * @Author: Sincez
 * @Date:   2016-11-20 23:46:52
 * @Last Modified by:   Sincez
 * @Last Modified time: 2016-12-06 10:58:32
 */
namespace Common\Model;
use Think\Model;
class Diamond1Model extends Model
{
	protected $tableName = 'diamond_1';

	public function getTotal($row){
		$aoption= 'status = 0';
		$gold = $row['dweight'];
		$gold_arr = array_unique(array_filter(explode('-',$gold)));
		if($gold_arr){
			if($gold_arr[0]){
				$aoption .= " and dweight >='".($gold_arr[0])."'";
			}
			if($gold_arr[1]){
				$aoption .= " and dweight <='".($gold_arr[1])."'";
			}
			// $condition.=" or (price >= '{$price_arr[0]}' $price_option) ";
		}
		if($row['dcertificate']){
			$aoption .= ' and dcertificate like"%'.$row['dcertificate'].'%"';
		}
		$price = $row['price'];
		$price_arr = array_unique(array_filter(explode('-',$price)));
		if($price_arr){
			if($price_arr[0]){
				$aoption .= " and bakeup3*$percent >='{$price_arr[0]}'";
			}
			if($price_arr[1]){
				$aoption .= " and bakeup3*$percent <='{$price_arr[1]}'";
			}
			// $condition.=" or (price >= '{$price_arr[0]}' $price_option) ";
		}
		$condition = '';
		$yellow = $row['yellow'];
		$yellow_arr = array_unique(array_filter(explode(',',$yellow)));
		if($yellow_arr){
			$newarr[] = $yellow_arr;
			$col_name[] = 'yellow';
		}


		$clarity = $row['clarity'];
		$clarity_arr = array_unique(array_filter(explode(',',$clarity)));
		if($clarity_arr){
			$newarr[] = $clarity_arr;
			$col_name[] = 'clarity';
		}

		$shape = $row['shape'];
		$shape_arr = array_unique(array_filter(explode(',',$shape)));
		if($shape_arr){
			$newarr[] = $shape_arr;
			$col_name[] = 'shape';
		}

		$cut = $row['cut'];
		$cut_arr = array_unique(array_filter(explode(',',$cut)));
		if($cut_arr){
			$newarr[] = $cut_arr;
			$col_name[] = 'cut';
		}

		$fluorescence = $row['fluorescence'];
		$fluorescence_arr = array_unique(array_filter(explode(',',$fluorescence)));
		if($fluorescence_arr){
			$newarr[] = $fluorescence_arr;
			$col_name[] = 'fluorescence';
		}

		$polishing = $row['polishing'];
		$polishing_arr = array_unique(array_filter(explode(',',$polishing)));
		if($polishing_arr){
			$newarr[] = $polishing_arr;
			$col_name[] = 'polishing';
		}

		$dtype = $row['dtype'];
		$dtype_arr = array_unique(array_filter(explode(',',$dtype)));
		if($dtype_arr){
			$newarr[] = $dtype_arr;
			$col_name[] = 'dtype';
		}

		$symmetry = $row['symmetry'];
		$symmetry_arr = array_unique(array_filter(explode(',',$symmetry)));
		if($symmetry_arr){
			$newarr[] = $symmetry_arr;
			$col_name[] = 'symmetry';
		}

		$new = getArrSet($newarr);
		foreach($new as $v){
			if($aoption){
				$con.= " or (".$aoption." and ";
			} else {
				$con.= " or (";
			}
			foreach($v as $kk=>$vv){
				if($kk == 0){
					$con.= ($col_name[$kk]."='{$vv}' ");
				} else {
					$con.= (' and '.$col_name[$kk]."='{$vv}' ");
				}
				
			}
			$con.= ")";
		}
		if($new){
			$condition = substr($con,3);
		} else {
			$condition = $aoption;
		}
		
		$where['_string'] = $condition;
		if($row['max_weight']&&$row['min_weight'])
		{
			$where['dweight'] = array(array('egt',$row['min_weight']),array('elt',$row['max_weight']));
		}
		$list = $this->where($where)->count();//echo $this->getLastsql();die;
		//echo $list;
		return $list;
	}

	public function getData($row,$percent){
		$aoption= 'status = 0';
		$gold = $row['dweight'];
		$gold_arr = array_unique(array_filter(explode('-',$gold)));
		if($gold_arr){
			if($gold_arr[0]){
				$aoption .= " and dweight >='".($gold_arr[0])."'";
			}
			if($gold_arr[1]){
				$aoption .= " and dweight <='".($gold_arr[1])."'";
			}
			// $condition.=" or (price >= '{$price_arr[0]}' $price_option) ";
		}
		if($row['dcertificate']){
			$aoption .= ' and dcertificate like"%'.$row['dcertificate'].'%"';
		}
		$price = $row['price'];
		$price_arr = array_unique(array_filter(explode('-',$price)));
		if($price_arr){
			if($price_arr[0]){
				$aoption .= " and bakeup3*$percent >='{$price_arr[0]}'";
			}
			if($price_arr[1]){
				$aoption .= " and bakeup3*$percent <='{$price_arr[1]}'";
			}
			// $condition.=" or (price >= '{$price_arr[0]}' $price_option) ";
		}
		$condition = '';
		$yellow = $row['yellow'];
		$yellow_arr = array_unique(array_filter(explode(',',$yellow)));
		if($yellow_arr){
			$newarr[] = $yellow_arr;
			$col_name[] = 'yellow';
		}


		$clarity = $row['clarity'];
		$clarity_arr = array_unique(array_filter(explode(',',$clarity)));
		if($clarity_arr){
			$newarr[] = $clarity_arr;
			$col_name[] = 'clarity';
		}

		$shape = $row['shape'];
		$shape_arr = array_unique(array_filter(explode(',',$shape)));
		if($shape_arr){
			$newarr[] = $shape_arr;
			$col_name[] = 'shape';
		}

		$cut = $row['cut'];
		$cut_arr = array_unique(array_filter(explode(',',$cut)));
		if($cut_arr){
			$newarr[] = $cut_arr;
			$col_name[] = 'cut';
		}

		$fluorescence = $row['fluorescence'];
		$fluorescence_arr = array_unique(array_filter(explode(',',$fluorescence)));
		if($fluorescence_arr){
			$newarr[] = $fluorescence_arr;
			$col_name[] = 'fluorescence';
		}

		$polishing = $row['polishing'];
		$polishing_arr = array_unique(array_filter(explode(',',$polishing)));
		if($polishing_arr){
			$newarr[] = $polishing_arr;
			$col_name[] = 'polishing';
		}

		$dtype = $row['dtype'];
		$dtype_arr = array_unique(array_filter(explode(',',$dtype)));
		if($dtype_arr){
			$newarr[] = $dtype_arr;
			$col_name[] = 'dtype';
		}

		$symmetry = $row['symmetry'];
		$symmetry_arr = array_unique(array_filter(explode(',',$symmetry)));
		if($symmetry_arr){
			$newarr[] = $symmetry_arr;
			$col_name[] = 'symmetry';
		}

		$new = getArrSet($newarr);
		foreach($new as $v){
			if($aoption){
				$con.= " or (".$aoption." and ";
			} else {
				$con.= " or (";
			}
			foreach($v as $kk=>$vv){
				if($kk == 0){
					$con.= ($col_name[$kk]."='{$vv}' ");
				} else {
					$con.= (' and '.$col_name[$kk]."='{$vv}' ");
				}
				
			}
			$con.= ")";
		}
		if($new){
			$condition = substr($con,3);
		} else {
			$condition = $aoption;
		}
		
		if($row['ids']){
			$where['id'] = array('in',explode(',', $row['ids']));
		}
		$where['_string'] = $condition;
		if($row['max_weight']&&$row['min_weight'])
		{
			$where['dweight'] = array(array('egt',$row['min_weight']),array('elt',$row['max_weight']));
		}

		if($row['length']){
			$this->limit($row['length']);
		}
		if($row['page']){
			$this->page($row['page']);
		}
		//dump($where);
		$list = $this->where($where)->select();//echo $this->getLastsql();die;
		//echo $list;
		return $list;
	}
	public function changeStatus($status,$old_status,$did)
	{
		return $this->where('did="'.$did.'" and status='.$old_status)->setField(array('status'=>$status));
	}
}