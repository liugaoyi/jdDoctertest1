<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index()
    {
        $id = "1";
        $origin = M('order')->where(array('order_id'=>$id))->getField('origin');//查询t_order表获得order_id
        echo $origin;
        $goods_id = M('order_goods')->where(array('order_id'=>$id))->getField('goods_id');
        echo $goods_id."<br/>";
        $prefix = C('DB_PREFIX');//设置$prefix为表前缀
        echo $prefix."<br/>";
        //alias('g')设置t_good表别名为g
        $goods_parts_list =
            M('goods')->alias('g')->join($prefix . 'goods_parts gp on gp.goods_id=g.goods_id')->
            where(array('gp.parent_id'=>$goods_id))->field('g.*')->order('gp.sort asc')->select();;
        dump($goods_parts_list);

        echo M('goods')->getlastsql();//调试方法
        $this->display();
    }

    /**
     * 提交维修帐单
     */
    public function repair_order()
    {
        $goods_ids = I('goods_id');
        $choices = I('choice');
        $nums = I('num');

        $order_id = I('order_id',0,'intval');
        if (!$order_id){
            $this->error("非法操作");
        }
        $repair_type = I('repair_type',0,'intval');
        M('order')->where(array('order_id'=>$order_id))->setField(array('repair_type'=>$repair_type));
        $parent_order = M('order')->where(array('order_id'=>$order_id))->find();
        $pre_order = M('order')->where(array('parent_sn'=>$parent_order['order_sn'],'pay_status'=>0))->select();
        foreach($pre_order as $v){
            M('order')->where(array('order_id'=>$v['order_id']))->setField('order_status',5);
        }
        $parent_id = M('order_goods')->where(array('order_id'=>$order_id))->getField('goods_id');

        $orderLogic = new OrderLogic();

        $goods_list = array();
        foreach($choices as $k=>$v){
            $result = $orderLogic->addGoods($goods_list, $goods_ids[$k], $nums[$k], '', $parent_order['user_id'], $parent_id, 2, array());
            if ($result['status'] < 0) {
                $this->ajaxReturn(array('err_code' => $result['status'], 'err_msg' => $result['msg']));
            }
        }

        $result = calculate_price($this->user_id, $goods_list, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        if ($result['status'] < 0) {
            $this->error($result['msg'], $this->refer_url);
        }
        if ($result['order_amount']<0){
            $this->error('账单不能为负数。', $this->refer_url);
        }

        // 订单满额优惠活动
        $order_prom = get_order_promotion($result['result']['order_amount']);
        $result['result']['order_amount'] = $order_prom['order_amount'];
        $result['result']['order_prom_id'] = $order_prom['order_prom_id'];
        $result['result']['order_prom_amount'] = $order_prom['order_prom_amount'];

        $total_price = array(
            'postFee'           => $result['result']['shipping_price'], // 物流费
            'couponFee'         => $result['result']['coupon_price'], // 优惠券
            'balance'           => $result['result']['user_money'], // 使用用户余额
            'pointsFee'         => $result['result']['integral_money'], // 积分支付
            'payables'          => $result['result']['order_amount'], // 应付金额
            'goodsFee'          => $result['result']['goods_price'],// 商品价格
            'order_prom_id'     => $result['result']['order_prom_id'], // 订单优惠活动id
            'order_prom_amount' => $result['result']['order_prom_amount'], // 订单优惠活动优惠了多少钱
        );
        #endregion 商品信息及计价
        $other_data = array(
            'shopping_type'    => 2,
            'parent_sn'        => $parent_order['order_sn'],
            'delivery_time_id' => $parent_order['delivery_time_id'],
            'install_time_id'  => $parent_order['install_time_id'],
            'goods_list'       => $goods_list,
            'order_type'       => 'repair'
        );

        // 添加订单
        $result = $orderLogic->addOrder($parent_order['user_id'], 0, '', '', 0, $total_price, $other_data);
        if ($result['status'] < 1) {
            $this->ajaxReturn(array('err_code' => $result['status'], 'err_msg' => $result['msg']));
        } else {
            if($parent_order['origin'] == 2 && $parent_order['user_id'] == 0){
                //后台新建的厂家保修单，用户无需确认和支付，子订单仅为了统计和厂商之间的结算
                M('order')->where(array('order_id' => $order_id))->save(array('flow_status'=>5));
                M('order')->where(array('order_id' => $result['result']))->save(array('flow_status'=>3,'repair_status'=>6,'order_status'=>4));
            }else{
                #region 推送一条公众号消息到微信
                $order_info = M('order')->where(array('order_id' => $result['result']))->getField('order_sn');
                $user = M('users')->where(array('user_id' => $parent_order['user_id']))->find();
                if ($user['oauth'] == 'weixin') {
                    $wx_app = M('wx_public')->where(array('is_site_id' => 1))->find();
                    if ($wx_app) {
                        $jssdk = new WxJssdk($wx_app['appid'], $wx_app['appsecret']);
                        $data = array(
                            'touser'      => $user['openid'],
                            'template_id' => 'GDddI_zJJDeWV3zUspZ1CwPGOBast2ysC1S_YM5r6Wk', // 订单进展通知
                            'url'         => U('repair/payment@m', array('id' => $result['result'])),
                            'topcolor'    => '#FF0000',
                            'data'        => array(
                                'first'    => array(
                                    'value' => "{$user['nickname']}您好，师傅已完成维修前检测，维修项目和账单已生成",
                                    'color' => "#173177"
                                ),
                                'keyword1' => array(
                                    'value' => $order_info, // 订单编号
                                    'color' => "#173177"
                                ),
                                'keyword2' => array(
                                    'value' => "等待您确认维修项目并支付费用", // 订单进展
                                    'color' => "#173177"
                                ),
                                'remark'   => array(
                                    'value' => '请尽快支付，以免影响维修进度',
                                    'color' => "#173177"
                                )
                            )
                        );

                        $jssdk->push_tpl_msg($data);
                    }
                }
                #endregion 推送一条公众号消息到微信
            }
            logOrder($parent_order['order_id'], '师傅生成维修账单', '订单操作', $this->user_id);
            M('order')->where(array('order_id'=>$result['result']))->setField('flow_uid',$this->user_id);
            $this->ajaxReturn(array('err_code' => 0, 'err_msg' => '添加成功', 'data' => array('order_id' => $result['result'])));
        }
    }

}