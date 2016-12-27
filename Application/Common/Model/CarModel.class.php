<?php
/**
 * Created by Whb
 * Author: Whb
 * Date: 2016/11/23
 * Time: 09:49
 */

namespace Common\Model;
use Think\Model;


class CarModel extends Model
{
    protected $tableName = 'Car';

    public function getCount($uid)
    {
        $where['uid'] =  $uid;
        $where['del'] = 0;
        return $this->alias('a')->field('a.*')->where($where)->count();
    }

    /**
     * @param $uid
     * @param string $type 1成品现货2裸钻3定制
     * @param int $del
     * @param array $ids
     * @return mixed
     */
    public function getData($uid, $type='',$del = 0,$ids = array())
    {
        $where['a.uid'] =  $uid;
        $where['a.del'] = 0;
        if(!empty($ids))
        {
            $where['a.id'] = array('in',$ids);
        }
        $fields = 'a.*';
        if(!empty($type))
        {
            $where['a.type'] = $type;
        }
        switch($type)
        {
            case 1:
                $join[1] = array(
                    'string'    =>  '__PRODUCT___1 as b on b.pid=a.pid',
                    'way'   =>  'left'
                );
                $fields .= ',b.price,b.myellow,b.clarity,b.pic1,b.pname,b.style,b.code,b.mweight,b.gross,b.inch';
                break;
            case 2:
                $join[2] = array(
                    'string'    =>  '__DIAMOND___1 as c on c.did=a.did',
                    'way'       =>  'left'
                );
                $fields .= ',c.price,c.yellow as myellow,c.clarity,c.did pname,c.dcertificate,c.shape,c.dweight,c.yellow,c.clarity,c.cut,c.polishing,c.symmetry,c.fluorescence,c.intprice,c.discount,c.price,c.bakeup3';
                break;
            case 3:
                $join[3] = array(
                    'string'    =>   '__CUSTOM___1 as d on d.style=a.style',
                    'way'       =>   'left'
                );
                $fields .= ',d.style,d.au750price,d.pt950price,d.pd950price,d.picUrl1 as pic1,d.pcategory';
                break;
            default:
                $join[1] = array(
                    'string'    =>  '__PRODUCT___1 as b on b.pid=a.pid',
                    'way'   =>  'left'
                );
                $fields .= ',b.price,b.myellow,b.clarity,b.pic1,b.pname';
                break;
        }
        $where['a.del'] = $del;
        foreach($join as $k=>$v)
        {
            $this->join($v['string'],$v['way']);
        }
        return $this
            ->field($fields)
            ->alias('a')
            ->order('id desc')
            ->where($where)->select();

    }
}