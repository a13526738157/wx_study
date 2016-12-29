<?php

function setCurUrl($append_param){
    $param = I('get.');
    $last_param = array_merge($param,$append_param);
    $url = U(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$last_param);
    return $url;
}
function getGroupName($gids,$group){
    if(!is_array($gids)){
        $gids = explode(',',$gids);
    }
    foreach($gids as $v){
        $groupname .= ($group[$v]['title'].'、');
    }
    return trim($groupname,'、');
}

function getAllName($gids,$arr,$colname=''){
    if(!is_array($gids)){
        $gids = explode(',',$gids);
    }
    foreach($gids as $v){
        if($colname){
            $allname .= ($arr[$v][$colname].'、');
        } else {
           $allname .= ($arr[$v].'、'); 
        }
    }
    return trim($allname,'、');
}

function getNoticChecks()
{
    if(session('userinfo.groupid')==1)
    {
        $uid = session('userinfo.uid');
        unset($map);
        $map['from'] = $uid;
        $map['ischeck'] = 2;
        $num = M('users')->where($map)->count();
        return $num;
    }else
    {
        return false;
    }
}

/**
     * 创建文件并写入数据
     * @param array $tableTitle 标题数据，键值为单元格的位置，例如：array('A1'=>'姓名','B1'=>'年龄')
     * @param array $bodyData 主体数据，格式为二维数组，第二维键值为单元格位置，例如：array(array('A1'=>'张三','B1'=>'25'),array('A2'=>'李四','B2'=>'26'))
     * @param array $property 表属性
     */
    function writeExcel($tableTitle, $bodyData,  $property = array(),$export=true) {
        set_time_limit(0);
        import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer.Excel5");
        import("Org.Util.PHPExcel.IOFactory.php");
        $objPHPExcel = new PHPExcel();
        $activeSheet = $objPHPExcel->getActiveSheet();
        if($property['settitle']){
            $activeSheet->setTitle($property['settitle']);
        }
        $cursheet = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($tableTitle as $k => $v) {
            $cursheet->getStyle(str_replace(1,'',$k))->getNumberFormat()
                    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $cursheet->setCellValueExplicit($k, $v, PHPExcel_Cell_DataType::TYPE_STRING);
            $activeSheet->getStyle($k)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $activeSheet->getStyle(substr($k, 0, 1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $activeSheet->getColumnDimension(substr($k, 0, 1))->setAutoSize(true);
        }
        foreach ($bodyData as $k=>$v) {
            foreach ($v as $x => $z) {
                $cursheet->getStyle($x)->getNumberFormat()
                    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $cursheet->setCellValueExplicit($x,$z, PHPExcel_Cell_DataType::TYPE_STRING);
            }
        }
        $creator = isset($property['creator']) ? $property['creator'] : ''; //作者
        $modifier = isset($property['modifier']) ? $property['modifier'] : ''; //修改人
        $title = isset($property['title']) ? $property['title'] : ''; //标题
        $subject = isset($property['subject']) ? $property['subject'] : ''; //主题
        $description = isset($property['description']) ? $property['description'] : ''; //描述
        $keywords = isset($property['keywords']) ? $property['keywords'] : ''; //关键字
        $category = isset($property['category']) ? $property['category'] : ''; //分类
        $objPHPExcel->getProperties()
                ->setCreator($creator)->setLastModifiedBy($modifier)
                ->setTitle($title)->setSubject($subject)
                ->setDescription($description)
                ->setKeywords($keywords)
                ->setCategory($category);
        $objPHPExcel->setActiveSheetIndex(0);
        $format = $property['format'] ? $property['format'] : 'Excel5';
        $filename = $property['filename'] ? $property['filename'] : date('YmdHis').'.xls';
        if($export){
            header("Content-type:application/vnd.ms-excel" );
            header("Content-Disposition:filename=" . iconv('utf-8', 'gb2312', $filename));
            header('Pragma: cache');
            header('Cache-Control: public, must-revalidate, max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $format);
            $objWriter->save('php://output');
        }else{
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $format);
            $objWriter->save($filename);
        }


    }

    function exportExcel($titlename,$row,$filename,$export=true){
        $col = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $t = array_keys($titlename);
        foreach($t as $k=>$v){
            $title[$col[$k].'1'] = $titlename[$v];   
        }
        $ad_type = C('ad_type');
        $ditch = M('ditch')->select();
        $ditch = array_coltokey($ditch,'id');
        $i = 2;//print_r($row);die;
        foreach($row as $v){
            if(!array_filter($v))continue;
            foreach($t as $a=>$c){
                if($c == 'click_rate'){
                    $body[$i][$col[$a] . $i] = round($v['click']/$v['impression']*100,2).'%';
                } elseif($c == 'click_rate_full'){
                    $body[$i][$col[$a] . $i] = round($v['click_full']/$v['impression_full']*100,2).'%';
                } elseif($c == 'fill_rate'){
                    $body[$i][$col[$a] . $i] = round($v['impression_full']/$v['flow']*100,2).'%';
                } elseif($c == 'ad_type'){
                    $body[$i][$col[$a] . $i] = $ad_type[$v[$c]];
                } elseif($c == 'ditch_name'){
                    $body[$i][$col[$a] . $i] = $ditch[$v['ditch_id']]['name'];
                } else{
                    $body[$i][$col[$a] . $i] = $v[$c];
                } 
            }
            $i++;
        }
        writeExcel($title,$body,array('filename'=>$filename),$export);
    }

    function verify_code($verifycode){
        $verify = new \Think\Verify(array('reset'=>false));
        if(!empty($verifycode) && !$verify->check($verifycode)){
            exit(json_encode(array('error'=>l('verify_code').l('wrong'))));
        }
    }
    /**
    *更换url参数
    *@param     string $url 原url
    *@param     string $to  替换内容 默认 ‘add’
    *@param     string $delimiter url分隔符 默认 ／
    *@param     int $seg 需要替换的位置 默认2
    *@return    string 新的url
    */
    function get_nurl($url,$to='add',$delimiter='/',$seg = 2)
    {
        $urlarr = explode($delimiter,$url);
        $urlarr[$seg] = $to;
        $url = join($delimiter,$urlarr);
        return $url;
    }
    /**
    *记录登录日志
    *@param  $username 用户名
    *@param  $type 类型   array(0   =>  '全部',1   =>  '登录成功', 2   =>  '密码错误',3   =>  '禁止登录'),
    *@param  $password 密码明文
    *@return void
    */
    function loginLog($username,$type,$password='')
    {
        if(empty($username))return false;
        $data['username'] = $username;
        $data['type'] = $type;
        $data['password'] = $password;
        $data['ip'] = get_client_ip();
        D('LoginLog')->insertData($data);
    }
    /**
     * 行为日志记录
     * @param  [String] $content [日志内容]
     * @return
     */
    function action_log($content){
        $admininfo = session('admininfo');
        $row['uid'] = $admininfo['id'];
        $row['username'] = $admininfo['username'];
        $row['ip'] = get_client_ip();
        $row['remark'] = $content;
        $row['url'] = $_SERVER["REQUEST_URI"];
        $model = D('ActionLog');
        $model->insertData($row);
    }