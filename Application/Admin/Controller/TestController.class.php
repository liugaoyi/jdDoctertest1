<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/24
 * Time: 11:58
 */

namespace Admin\Controller;
use Think\Controller;
class TestController extends Controller{

    public function main(){
        $id = "3";//设置一个假id
        $origin = M('order')->where(array('order_id'=>$id))->getField('origin');//查询t_order表获得order_id也就是订单id
        $goods_id = M('order_goods')->where(array('order_id'=>$id))->getField('goods_id');//获得order_goods表商品id
        $prefix = C('DB_PREFIX');//设置$prefix为表前缀

        //$model = D();
        //$sql = "SELECT g.* ,gp.* FROM t_goods g INNER JOIN t_goods_parts gp on gp.goods_id=g.goods_id WHERE gp.parent_id = '3' ORDER BY gp.sort asc";
        //$goods_parts_list = $model->query($sql);
        $goods_parts_list =
            M('goods')->alias('g')->join($prefix . 'goods_parts gp on gp.goods_id=g.goods_id')->
            where(array('gp.parent_id'=>$goods_id))->field('g.*')->order('gp.sort asc')->select();
        $this->assign('goods_parts_list',$goods_parts_list);
        $this->assign('order_id',$id);
        $this->assign('origin',$origin);
        $this->display();
    }


    public function test(){
        $id = "3";//设置一个假id
        $origin = M('order')->where(array('order_id'=>$id))->getField('origin');//查询t_order表获得order_id也就是订单id
        $goods_id = M('order_goods')->where(array('order_id'=>$id))->getField('goods_id');//获得order_goods表商品id
        $prefix = C('DB_PREFIX');//设置$prefix为表前缀
        $goods_parts_list =
            M('goods')->alias('g')->join($prefix . 'goods_parts gp on gp.goods_id=g.goods_id')->
            where(array('gp.parent_id'=>$goods_id))->field('g.*')->order('gp.sort asc')->select();
        $this->assign('goods_parts_list',$goods_parts_list);
        $this->assign('order_id',$id);
        $this->assign('origin',$origin);
        $this->display();
    }

    public function test2(){
        $id = "3";//设置一个假id
        $origin = M('order')->where(array('order_id'=>$id))->getField('origin');//查询t_order表获得order_id也就是订单id
        $goods_id = M('order_goods')->where(array('order_id'=>$id))->getField('goods_id');//获得order_goods表商品id
        $prefix = C('DB_PREFIX');//设置$prefix为表前缀
        $goods_parts_list =
            M('goods')->alias('g')->join($prefix . 'goods_parts gp on gp.goods_id=g.goods_id')->
            where(array('gp.parent_id'=>$goods_id))->field('g.*')->order('gp.sort asc')->select();
        $this->assign('goods_parts_list',$goods_parts_list);
        if(is_post){
            echo json_encode($goods_parts_list);
        }
    }

}