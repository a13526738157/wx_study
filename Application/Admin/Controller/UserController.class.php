<?php
namespace Admin\Controller;
use Common\Controller\AdminController;
class UserController extends AdminController
{
    public function _initialize(){
    	parent::_initialize();
    	//面包屑
    	$this->subscribe = C('SUBSCRIBE');
    	$this->breads = array(
    		array('title' => '用户管理','key'=>1,'url'=>U('user/index')),
   			);
	}
	public function index(){	//name:用户管理
		$this->init = array(
			'title'	=>	'用户管理',
			'key'	=>	1,
			'cur'	=>	'userList'
			);
		$do = I('do');
		if($do == 'listTable'){
			//TODO:现在单用户 后期多用户通过配置改为多用户
			$user_model = D('User');
			$lists = $user_model->getList();
			$i = 1;
			$subscribe = $this->subscribe;
			foreach ($lists as $k => $v) {
				$op = '<div class="tpl-table-black-operation">';
				$op .= '<a href="javascript:;"><i class="am-icon-pencil"></i> 修改密码</a>';
				$op .= ' <a href="javascript:;" class="tpl-table-black-operation-del"><i class="am-icon-trash"></i> 删除</a>';
                $op .= '</div>';
				$arr[] = array(
					$i++,
					$v['username'],
					$v['nickname'],
					$v['create_time'],
					$subscribe[$v['subscribe']],
					$op
 					);
			}
			$backData['data'] = $arr?$arr:array();
			//$backData['sql'] = D('User')->getLastSql();
			//总数
            $row['start'] = I('start',0);//当前第几页
            $row['length'] = I('length',10);//每页数据条数

            $res = $user_model->getCount($row);

            $reportCount = $res;
            $backData["start"] = $row['start'];
            $backData['length'] = $row['length'];
            $backData["recordsTotal"] = $reportCount?$reportCount:0;
            $backData["recordsFiltered"] = $reportCount?$reportCount:0;
			$this->ajaxReturn($backData);
		}
		$this->display();
	}
}