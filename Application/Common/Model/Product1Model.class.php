<?php
/**
 * @Author: Sincez
 * @Date:   2016-11-20 23:33:21
 * @Last Modified by:   Sincez
 * @Last Modified time: 2016-12-04 20:45:56
 */
namespace Common\Model;
use Think\Model;
class Product1Model extends Model
{
	protected $tableName = 'product_1';

	public function getData($row,$percent){
		$condition = '';

		// if($row['gold_min']){
		// 	$aoption .= ' and mweight>="'.$row['gold_min']."'";
		// }
		// if($row['gold_max']){
		// 	$aoption .= ' and mweight<="'.$row['gold_max']."'";
		// }
		$aoption= 'status = 0';
		if($row['pcategory_all']){
			$pcategory_str = join(',',$row['pcategory_all']);
			$pcategory_str = "'".str_replace(",","','",$pcategory_str)."'";
			$aoption .= " and pcategory in ($pcategory_str)";
		}
		if($row['serie_all']){
			$serie_str = implode(',',$row['serie_all']);
			$serie_str = "'".str_replace(",","','",$serie_str)."'";
			$aoption .= " and serie in ($serie_str)";
		}
		$gold = $row['gold'];
		$gold_arr = array_unique(array_filter(explode('-',$gold)));
		if($gold_arr){
			if($gold_arr[0]){
				$aoption .= " and mweight >='{$gold_arr[0]}'";
			}
			if($gold_arr[1]){
				$aoption .= " and mweight <='{$gold_arr[1]}'";
			}
			// $condition.=" or (price >= '{$price_arr[0]}' $price_option) ";
		}
		if($row['style']){
			$aoption .= ' and style like"%'.$row['style'].'%"';
		}

		$price = $row['price'];
		$price_arr = array_unique(array_filter(explode('-',$price)));
		if($price_arr){
			if($price_arr[0]){
				$aoption .= " and price*$percent >='{$price_arr[0]}'";
			}
			if($price_arr[1]){
				$aoption .= " and price*$percent <='{$price_arr[1]}'";
			}
			// $condition.=" or (price >= '{$price_arr[0]}' $price_option) ";
		}

		$yellow = $row['yellow'];
		$yellow_arr = array_unique(array_filter(explode(',',$yellow)));
		if($yellow_arr){
			$newarr[] = $yellow_arr;
		}
		// if($yellow_arr){
		// 	foreach($yellow_arr as $v){
		// 		$condition.=" or yellow = '{$v}' ";
		// 	}
		// }

		$pcategory = $row['pcategory'];
		$pcategory_arr = array_unique(array_filter(explode(',',$pcategory)));
		if($pcategory_arr){
			$newarr[] = $pcategory_arr;
		}
		// if($pcategory_arr){
		// 	foreach($pcategory_arr as $v){
		// 		$condition.=" or pcategory = '{$v}' ";
		// 	}
		// }

		$serie = $row['serie'];
		$serie_arr = array_unique(array_filter(explode(',',$serie)));
		if($serie_arr){
			$newarr[] = $serie_arr;
		}
		// if($serie_arr){
		// 	foreach($serie_arr as $v){
		// 		$condition.=" or serie = '{$v}' ";
		// 	}
		// }
		$new = getArrSet($newarr);
		foreach($new as $v){
			if($aoption){
				$con.= " or (".$aoption." and ";
			} else {
				$con.= " or (";
			}
			foreach($v as $kk=>$vv){
				if($yellow_arr){
					if($kk == 0){
						$con.= "yellow='{$vv}' ";
					} elseif($kk == 1){
						$con.= " and pcategory='{$vv}' ";
					} elseif($kk == 2){
						$con.= " and serie='{$vv}' ";
					}
				} elseif(!$yellow_arr){
					if($pcategory_arr){
						if($kk == 0){
							$con.= "pcategory='{$vv}' ";
						} elseif($kk == 1){
							$con.= " and serie='{$vv}' ";
						}
					} else {
						if($kk == 0){
							$con.= "serie='{$vv}' ";
						}
					}
					
				}
				
			}
			// $con = substr($con,2);
			$con.= ")";
			// $condition="";
		}
		if($new){
			$condition = substr($con,3);
		} else {
			$condition = $aoption;
		}
		//echo $con;die;

			// echo substr($con,3);die;
			// if(count($serie_arr)>=count($pcategory_arr) && count($pcategory_arr)>=count($yellow_arr) && count($yellow_arr)>0){
			// 	foreach($serie_arr as $v){
			// 		foreach($pcategory_arr as $vp){
			// 			foreach($yellow_arr as $vy){
			// 				$condition.=" or (serie = '{$v}' and pcategory = '{$vp}' and yellow='{$vy}')";
			// 			}
			// 		}
			// 	}
			// } elseif(count($serie_arr)>=count($pcategory_arr) && count($pcategory_arr)<=count($yellow_arr) && count($yellow_arr)>0){
			// 	foreach($serie_arr as $v){
			// 		foreach($yellow_arr as $vy){
			// 			foreach($pcategory_arr as $vp){
			// 				$condition.=" or (serie = '{$v}' and pcategory = '{$vp}' and yellow='{$vy}')";
			// 			}
			// 		}
			// 	}
			// } elseif(count($serie_arr)>=count($yellow) && count($yellow)>=count($pcategory_arr) && count($pcategory_arr)>0){
			// 	foreach($serie_arr as $v){
			// 		foreach($yellow_arr as $vy){
			// 			foreach($pcategory_arr as $vp){
			// 				$condition.=" or (serie = '{$v}' and pcategory = '{$vp}' and yellow='{$vy}')";
			// 			}
			// 		}
			// 	}
			// } elseif(count($serie_arr)>=count($yellow) && count($yellow)<=count($pcategory_arr) && count($pcategory_arr)>0){
			// 	foreach($serie_arr as $v){
			// 		foreach($pcategory_arr as $vp){
			// 			foreach($yellow_arr as $vy){
			// 				$condition.=" or (serie = '{$v}' and pcategory = '{$vp}' and yellow='{$vy}')";
			// 			}
			// 		}
			// 	}
			// } elseif(count($pcategory_arr)>=count($yellow) && count($yellow)<=count($serie_arr) && count($serie_arr)>0){
			// 	foreach($pcategory_arr as $vp){
			// 		foreach($yellow_arr as $vy){
			// 			foreach($serie_arr as $v){
			// 				$condition.=" or (serie = '{$v}' and pcategory = '{$vp}' and yellow='{$vy}')";
			// 			}
			// 		}
			// 	}
			// } elseif(count($pcategory_arr)>=count($yellow) && count($yellow)<=count($serie_arr) && count($serie_arr)>0){
			// 	foreach($pcategory_arr as $vp){
			// 		foreach($serie_arr as $v){
			// 			foreach($yellow_arr as $vy){
			// 				$condition.=" or (serie = '{$v}' and pcategory = '{$vp}' and yellow='{$vy}')";
			// 			}
			// 		}
			// 	}
			// }
			// if(count($yellow_arr) > 0 && count($yellow_arr) < count($pcategory_arr)){
			// 	if(count($pcategory_arr) > 0 && count($pcategory_arr) < count($serie_arr)){
			// 		foreach($serie_arr as $v){
			// 			foreach($pcategory_arr as $vp){
			// 				foreach($yellow_arr as $vy){
			// 					$condition.=" or (serie = '{$v}' and pcategory = '{$vp}' and yellow='{$vy}')";
			// 				}
			// 			}
			// 		}
			// 	} elseif(count($pcategory_arr) > 0) {
			// 		foreach($pcategory_arr as $vp){
			// 			foreach($serie_arr as $v){
			// 				foreach($yellow_arr as $vy){
			// 					$condition.=" or (serie = '{$v}' and pcategory = '{$vp}' and yellow='{$vy}')";
			// 				}
			// 			}
			// 		}
			// 	}
			// } elseif(count($yellow_arr)>0) {
			// 	if(count($pcategory_arr) > 0 && count($pcategory_arr) < count($serie_arr)){
			// 		foreach($yellow_arr as $vy){
			// 			foreach($serie_arr as $v){
			// 				foreach($pcategory_arr as $vp){
			// 					$condition.=" or (serie = '{$v}' and pcategory = '{$vp}' and yellow='{$vy}')";
			// 				}
			// 			}
			// 		}
			// 	} elseif(count($pcategory_arr) > 0 && count($pcategory_arr) > count($serie_arr)) {
			// 		foreach($yellow_arr as $vy){
			// 			foreach($pcategory_arr as $vp){
			// 				if($serie_arr){
			// 					foreach($serie_arr as $v){
			// 						$condition.=" or (serie = '{$v}' and pcategory = '{$vp}' and yellow='{$vy}')";
			// 					}
			// 				} else {
			// 					$condition.=" or (pcategory = '{$vp}' and yellow='{$vy}')";
			// 				}
							
			// 			}
			// 		}				
			// 	} else {
			// 		foreach($yellow_arr as $vy){
			// 			if($pacategory_arr){
			// 				foreach($pacategory_arr as $vp){
			// 					if($serie_arr){
			// 						foreach($serie_arr as $v){
			// 							$condition.=" or (serie = '{$v}' and pcategory = '{$vp}' and yellow='{$vy}')";
			// 						}
			// 					} else {
			// 						$condition.=" or (pcategory = '{$vp}' and yellow='{$vy}')";
			// 					}
								
			// 				}
			// 			} else {
			// 				if($serie_arr){
			// 					foreach($serie_arr as $v){
			// 						$condition.=" or (serie = '{$v}' and yellow='{$vy}')";
			// 					}
			// 				} else {
			// 					$condition.=" or (yellow='{$vy}')";
			// 				}
			// 			}
						
						
			// 		}
			// 	}
			// }
			// echo $condition;die;
			
			// $gold = $row['gold'];
			// $gold_arr = array_unique(array_filter(explode('-',$gold)));
			// if($gold_arr){
			// 	if($gold_arr[1]){
			// 		$gold_option = "and gold <'{$gold_arr[1]}'";
			// 	}
			// 	$condition.=" or (gold >= '{$gold_arr[0]}' $gold_option) ";
			// }

			// $style = $row['style'];
			// if($style){
			// 	$condition.=" or (style = '{$style}') ";
			// }
			if($condition){
				$where['_string'] = $condition;
			}
			
			if($row['length']){
				$this->limit($row['length']);
			}
			if($row['page']){
				$this->page($row['page']);
			}
			// $where['status'] = 0;
			$list = $this->field('count(*) as num,style,max(price) as max_price,min(price) as min_price,max(inch) as max_inch,min(inch) as min_inch,max(mweight) as max_weight,min(mweight) as min_weight,pic1,pic2')
				->where($where)
				->group('style')
				->select();//echo $this->getLastsql();die;
			return $list;
	}
	public function changeStatus($status,$old_status,$pid)
	{
		return $this->where('pid="'.$pid.'" and status='.$old_status)->setField(array('status'=>$status));
	}
}