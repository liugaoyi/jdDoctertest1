<?php
/**
 * Created by PhpStorm.
 * Author: CONG
 * Date: 2017/1/4
 * Time: 5:37
 */

namespace Admin\Logic;

use Think\Model\RelationModel;

class OrderLogic extends RelationModel
{

    /**
     * @param array  $condition 搜索条件
     * @param string $order 排序方式
     * @param int    $start limit开始行
     * @param int    $page_size 获取数量
     */
    public function getOrderList($condition, $order = '', $start = 0, $page_size = 20)
    {
        $res = M('order')->where($condition)->limit("$start,$page_size")->order($order)->select();

        return $res;
    }

    /**
     * 获取订单商品详情
     * @param $order_id
     * @return mixed
     */
    public function getOrderGoods($order_id)
    {
        $sql = "SELECT g.*,o.*,(o.goods_num * o.member_goods_price) AS goods_total FROM __PREFIX__order_goods o " .
            "LEFT JOIN __PREFIX__goods g ON o.goods_id = g.goods_id WHERE o.order_id = $order_id";
        $res = $this->query($sql);

        return $res;
    }

    /**
     * 获取订单信息
     * @param int $order_id 订单id
     * @return mixed
     */
    public function getOrderInfo($order_id)
    {
        //  订单总金额查询语句
        $order = M('order')->where(array('order_id' => $order_id))->find();
        $order['address2'] = $this->getAddressName($order['province'], $order['city'], $order['district'], $order['town'], $order['street']);
        $order['address2'] = $order['address2'] . $order['address'];

        return $order;
    }

    /**
     * 根据商品型号获取商品
     * @param array $goods_id_arr 商品集合
     * @return array
     */
    public function get_spec_goods($goods_id_arr)
    {
        if (!is_array($goods_id_arr)) return false;
        $order_goods = array();
        foreach ($goods_id_arr as $key => $val) {
            $arr = array();
            $arr['goods_id'] = $key; // 商品id
            if (isset($val['spec_key'])) {
                foreach ($val['spec_key'] as $k => $v) {
                    $arr['goods_num'] = $v['goods_num']; // 购买数量
                    // 如果这商品有规格
                    if ($k != 'key') {
                        $arr['goods_spec'] = $k;
                    }
                }
            }
            if (isset($val['goods_flag'])) {
                $arr['goods_flag'] = $val['goods_flag'];
            }
            $order_goods[] = $arr;
        }

        return $order_goods;
    }

    /*
     * 订单操作记录
     */
    public function orderActionLog($order_id, $action, $note = '')
    {
        $o = new \Think\Model();
        $order = $o->query('SELECT * FROM `t_order` WHERE `order_id` = '.$order_id.' LIMIT 1');
        $data['order_id'] = $order_id;
        $data['action_user'] = get_admin_id();
        $data['action_note'] = $note;
        $data['order_status'] = $order[0]['order_status'];
        $data['pay_status'] = $order[0]['pay_status'];
        $data['shipping_status'] = $order[0]['shipping_status'];
        $data['log_time'] = time();
        $data['status_desc'] = $action;

        return M('order_action')->add($data);//订单操作记录
    }

    /*
     * 获取订单商品总价格
     */
    public function getGoodsAmount($order_id)
    {
        $sql = "SELECT SUM(goods_num * goods_price) AS goods_amount FROM __PREFIX__order_goods WHERE order_id = {$order_id}";
        $res = $this->query($sql);

        return $res[0]['goods_amount'];
    }

    /**
     * 得到配送单流水号
     */
    public function get_delivery_sn()
    {
        /* 选择一个随机的方案 */
        send_http_status('310');
        mt_srand((double)microtime() * 1000000);

        return date('YmdHi') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    /**
     * 获取当前可操作的按钮
     * @param array $order 订单主体
     * @return array
     */
    public function getOrderButton($order)
    {
        // 操作按钮汇总 ：付款、设为未付款、确认、取消确认、无效、去发货、确认收货、申请退货

        $os = $order['order_status'];//订单状态
        $ss = $order['shipping_status'];//发货状态
        $ps = $order['pay_status'];//支付状态
        $btn = array();
        if ($order['order_type']) {
            $service_status = 0; // 服务状态 0待确认 1已确认 2待跟进 3已跟进 4进行中 5已取消 6已完成 7已作废
            if ($order['order_type'] == 'disassembly') { // 拆装单
                $service_status = $order['disassembly_status']; //拆装
            }
            if ($order['order_type'] == 'recovery') { // 回收单
                $service_status = $order['recovery_status'];
            }
            if ($order['order_type'] == 'install') { // 安装单
                $service_status = $order['install_status'];
            }
            if ($order['order_type'] == 'maintenance') { // 保养单
                $service_status = $order['maintenance_status'];
            }
            if ($order['order_type'] == 'repair') { // 维修单
                $service_status = $order['repair_status'];
            }
            if ($ps == 0 && $os == 0) {
                $btn['pay'] = '付款';
                $btn['confirm'] = '确认';
            } elseif ($os == 0 && $ps == 1) {
                $btn['pay_cancel'] = '设为未付款';
                $btn['confirm'] = '确认';
            }
            if ($ps == 0 && $order['parent_sn'] != ''){
                $btn['pay'] = '付款';
            }
            if ($os == 1 && in_array($service_status, [0, 1])) {
                if ($ps == 0) {
                    $btn['pay'] = '付款';
                }
                $btn['cancel'] = '取消确认';
                if (empty($order['flow_uid'])) {
                    $btn['flow_issue'] = '派单';
                }
            }
            if ($os == 1 && in_array($service_status, [2, 5])) { // 已派单，待跟进
                $btn['flow_confirm'] = '确认跟进';
            }
            if ($os == 1 && in_array($service_status, [3, 4])) { // 已跟进
                $btn['flow_cancel'] = '取消跟进';
                $btn['service_confirm'] = '完成服务';
            }
            if ($os == 1 && $service_status == 6) { // 已完成
                $btn['confirm_order'] = '确认订单';
                $btn['refund'] = '申请退货';
            } elseif ($os == 2 || $os == 4) {
                $btn['refund'] = '申请退货';
            } elseif ($os == 3 || $os == 5) {
                $btn['remove'] = '移除';
            }
            if ($os != 5) {
                $btn['invalid'] = '无效';
            }
        }
        if (empty($order['order_type'])) {
            if ($order['pay_code'] == 'cod') {
                if ($os == 0 && $ss == 0) {
                    $btn['confirm'] = '确认';
                } elseif ($os == 1 && $ss == 0) {
                    $btn['delivery'] = '去发货';
                    $btn['cancel'] = '取消确认';
                } elseif ($ss == 1 && $os == 1 && $ps == 0) {
                    $btn['pay'] = '付款';
                } elseif ($ps == 1 && $ss == 1 && $os == 1) {
                    $btn['pay_cancel'] = '设为未付款';
                }
            } else {
                if ($ps == 0 && $os == 0) {
                    $btn['pay'] = '付款';
                } elseif ($os == 0 && $ps == 1) {
                    $btn['pay_cancel'] = '设为未付款';
                    $btn['confirm'] = '确认';
                } elseif ($os == 1 && $ps == 1 && $ss == 0) {
                    $btn['cancel'] = '取消确认';
                    $btn['delivery'] = '去发货';
                }
            }

            if ($ss == 1 && $os == 1 && $ps == 1) {
                $btn['delivery_confirm'] = '确认收货';
                $btn['refund'] = '申请退货';
            } elseif ($os == 2 || $os == 4) {
                $btn['refund'] = '申请退货';
            } elseif ($os == 3 || $os == 5) {
                $btn['remove'] = '移除';
            }
            if ($os != 5) {
                $btn['invalid'] = '无效';
            }
        }

        return $btn;
    }

    /**
     * 订单处理方法集合
     * @param int    $order_id 订单id
     * @param string $act 动作
     * @param array  $other_data 其他信息
     * @return bool
     */
    public function orderProcessHandle($order_id, $act, $other_data = array())
    {
        $updata = array();
        switch ($act) {
            case 'pay': //付款
                $order_sn = M('order')->where(array('order_id' => $order_id))->getField("order_sn");
                update_pay_status($order_sn); // 调用确认收货按钮
                return true;
            case 'pay_cancel': //取消付款
                $updata['pay_status'] = 0;
                $this->order_pay_cancel($order_id);

                return true;
            case 'confirm': //确认订单
                $res = admin_confirm_order($order_id);

                return $res;
            case 'cancel': //取消确认
                $order = M('order')->where(array('order_id' => $order_id))->find();
                if ($order['order_type'] == 'disassembly') { // 拆装单
                    $updata['disassembly_status'] = 0;
                } elseif ($order['order_type'] == 'recovery') { // 回收单
                    $updata['recovery_status'] = 0;
                } elseif ($order['order_type'] == 'install') { // 安装单
                    $updata['install_status'] = 0;
                } elseif ($order['order_type'] == 'maintenance') { // 保养单
                    $updata['maintenance_status'] = 0;
                } elseif ($order['order_type'] == 'repair') { // 维修单
                    $updata['repair_status'] = 0;
                }
                $updata['order_status'] = 0;
                $res = M('order')->where(array('order_id' => $order_id))->save($updata);//改变订单状态

                // 更新关联的子服务单状态
                update_sub_order_status($order['order_sn']);

                return $res;
            case 'invalid': //作废订单
                $order = M('order')->where(array('order_id' => $order_id))->find();
                if ($order['order_type'] == 'disassembly') { // 拆装单
                    $updata['disassembly_status'] = 7;
                } elseif ($order['order_type'] == 'recovery') { // 回收单
                    $updata['recovery_status'] = 7;
                } elseif ($order['order_type'] == 'install') { // 安装单
                    $updata['install_status'] = 7;
                } elseif ($order['order_type'] == 'maintenance') { // 保养单
                    $updata['maintenance_status'] = 7;
                } elseif ($order['order_type'] == 'repair') { // 维修单
                    $updata['repair_status'] = 7;
                }
                $updata['order_status'] = 5;
                $res = M('order')->where(array('order_id' => $order_id))->save($updata);

                // 更新关联的子服务单状态
                update_sub_order_status($order['order_sn']);

                return $res;
            case 'remove': //移除订单
                return $this->delOrder($order_id);
            case 'delivery_confirm'://确认收货
                confirm_order($order_id); // 调用确认收货按钮
                return true;
            case 'confirm_order': // 确认订单
                confirm_order($order_id);

                return true;
            case 'flow_issue': // 派单
                $flow_uid = $other_data['flow_uid'];
                if ($flow_uid) {
                    $res = order_flow_issue($order_id, $flow_uid);

                    return $res;
                } else {
                    return true;
                }
            case 'flow_confirm': // 确认跟进
                $res = order_flow_confirm($order_id);

                return $res;
            case 'flow_cancel': // 取消跟进
                $res = order_flow_cancel($order_id);

                return $res;
            case 'service_confirm': // 确认完成服务
                $res = order_service_confirm($order_id);

                return $res;
            case 'auto_split_order': // 自动拆单
                $res = auto_split_order($order_id);

                return $res;
            default:
                return true;
        }
    }

    /**
     * 管理员取消付款
     * @param int $order_id 订单id
     * @return bool
     */
    function order_pay_cancel($order_id)
    {
        //如果这笔订单已经取消付款过了
        $count = M('order')->where(array('order_id' => $order_id, 'pay_status' => 1))->count();
        if ($count == 0) {
            return false;
        }
        $order = M('order')->where(array('order_id' => $order_id))->find();
        // 增加对应商品的库存
        $orderGoodsArr = M('OrderGoods')->where(array('order_id' => $order_id))->select();
        foreach ($orderGoodsArr as $key => $val) {
            if (!empty($val['spec_key'])) {
                // 有选择规格的商品, 还原规格表里面库存
                M('SpecGoodsPrice')->where(array('goods_id' => $val['goods_id'], 'key' => $val['spec_key']))->setInc('store_count', $val['goods_num']);
                refresh_stock($val['goods_id']);
            } else {
                M('Goods')->where(array('goods_id' => $val['goods_id']))->setInc('store_count', $val['goods_num']); // 还原商品库存
            }
            M('Goods')->where(array('goods_id' => $val['goods_id']))->setDec('sales_sum', $val['goods_num']); // 减少商品销售量
            //更新活动商品购买量
            if ($val['prom_type'] == 1 || $val['prom_type'] == 2) {
                $prom = get_goods_promotion($val['goods_id']);
                if ($prom['is_end'] == 0) {
                    $table_name = $val['prom_type'] == 1 ? 'flash_sale' : 'group_buy';
                    M($table_name)->where(array('id' => $val['prom_id']))->setDec('buy_num', $val['goods_num']);
                    M($table_name)->where(array('id' => $val['prom_id']))->setDec('order_num');
                }
            }
        }
        // 根据order表查看消费记录 给他会员等级升级 修改他的折扣 和 总金额
        M('order')->where(array('order_id' => $order_id))->save(array('pay_status' => 0));
        update_user_level($order['user_id']);
        // 记录订单操作日志
        logOrder($order['order_id'], '订单取消付款', '付款取消', $order['user_id']);
        // 更新关联的子服务单状态
        update_sub_order_status($order['order_sn']);
    }

    /**
     * 处理配送单
     * @param array $data 查询数量
     * @return bool
     */
    public function deliveryHandle($data)
    {
        $order = $this->getOrderInfo($data['order_id']);
        $orderGoods = $this->getOrderGoods($data['order_id']);
        $selectgoods = $data['goods'];
        $data['order_sn'] = $order['order_sn'];
        $data['delivery_sn'] = $this->get_delivery_sn();
        $data['zipcode'] = $order['zipcode'];
        $data['user_id'] = $order['user_id'];
        $data['admin_id'] = get_admin_id();
        $data['consignee'] = $order['consignee'];
        $data['mobile'] = $order['mobile'];
        $data['country'] = $order['country'];
        $data['province'] = $order['province'];
        $data['city'] = $order['city'];
        $data['district'] = $order['district'];
        $data['address'] = $order['address'];
        $data['shipping_code'] = $order['shipping_code'];
        $data['shipping_name'] = $order['shipping_name'];
        $data['shipping_price'] = $order['shipping_price'];
        $data['ctime'] = time();
        $did = M('delivery_order')->add($data);
        $is_delivery = 0;
        foreach ($orderGoods as $k => $v) {
            if ($v['is_send'] == 1) {
                $is_delivery++;
            }
            if ($v['is_send'] == 0 && in_array($v['rec_id'], $selectgoods)) {
                $res['is_send'] = 1;
                $res['delivery_id'] = $did;
                $r = M('order_goods')->where(array('rec_id' => $v['rec_id']))->save($res);//改变订单商品发货状态
                $is_delivery++;
            }
        }
        $updata['shipping_time'] = time();
        if ($is_delivery == count($orderGoods)) {
            $updata['shipping_status'] = 1;
        } else {
            $updata['shipping_status'] = 2;
        }
        M('order')->where(array('order_id' => $data['order_id']))->save($updata);//改变订单状态
        $s = $this->orderActionLog($order['order_id'], 'delivery', $data['note']);//操作日志
        return $s && $r;
    }

    /**
     * 获取地区名字
     * @param int $p 省
     * @param int $c 市
     * @param int $d 区
     * @param int $e 乡镇
     * @param int $f 街道
     * @return string
     */
    public function getAddressName($p = 0, $c = 0, $d = 0, $e = 0, $f = 0)
    {
        $p = M('region')->where(array('id' => $p))->field('name')->find();
        $c = M('region')->where(array('id' => $c))->field('name')->find();
        $d = M('region')->where(array('id' => $d))->field('name')->find();
        $e = M('region')->where(array('id' => $e))->field('name')->find();
        $f = M('region')->where(array('id' => $f))->field('name')->find();

        return $p['name'] . ' ' . $c['name'] . ' ' . $d['name'] . ' ' . $e['name'] . ' ' . $f['name'] . ' ';
    }

    /**
     * 删除订单
     * @param $order_id
     * @return bool
     */
    function delOrder($order_id)
    {
        $order = M('order')->where(array('order_id' => $order_id))->find();
        $a = M('order')->where(array('order_id' => $order_id))->save(array('is_del' => 1));
        $b = M('order_goods')->where(array('order_id' => $order_id))->save(array('is_del' => 1));

        // 更新关联的子服务单状态
        update_sub_order_status($order['order_sn']);

        return $a && $b;
    }

    /**
     * 获取日期内对应的服务时段服务预约配置
     * @param int $type 服务配置类型
     * @param int $start_time 开始日期时间戳(>=)
     * @param int $end_time 结束日期时间戳(<，按天自动+1)
     * @return array
     */
    public function get_reservation_daily_list($type, $start_time = null, $end_time = null)
    {
        $orderLogic = new \Common\Logic\OrderLogic();

        return $orderLogic->get_reservation_daily_list($type, $start_time, $end_time);
    }

    /**
     * 获取id对应的服务时段服务预约配置
     * @param int $id
     * @return array
     */
    public function get_reservation_daily($id)
    {
        $orderLogic = new \Common\Logic\OrderLogic();

        return $orderLogic->get_reservation_daily($id);
    }

    /**
     *  添加一个订单
     * @param int       $user_id 用户id
     * @param int|array $address_id 地址id
     * @param string    $shipping_code 物流编号
     * @param string    $invoice_title 发票
     * @param int       $coupon_id 优惠券id
     * @param array     $total_price 各种价格
     * @param array     $other_data 其他订单附加参数
     * @return int $order_id 返回新增的订单id
     */
    public function addOrder($user_id, $address_id, $shipping_code, $invoice_title, $coupon_id = 0, $total_price = array(), $other_data = array(),$channel = '')
    {
        $orderLogic = new \Common\Logic\OrderLogic();

        return $orderLogic->addOrder($user_id, $address_id, $shipping_code, $invoice_title, $coupon_id, $total_price, $other_data,$channel);
    }

    /**
     * 添加订单商品方法
     * @param array        $goods_list 商品列表
     * @param int|string   $goods_id 商品id
     * @param int          $goods_num 商品数量
     * @param array|string $goods_spec 选择规格
     * @param int          $user_id 用户id
     * @param int          $goods_parent_id 商品父级id
     * @param int          $goods_type 订单商品类型 0普通商品 2有子商品的父商品 2配件/服务类子商品 3套餐子商品 4服务项目
     * @param array        $goods_data 商品附加信息
     * @param string       $goods_flag 订单商品标识 disassembly:拆机 recovery:回收 install:安装
     * @return array
     */
    public function addGoods(array &$goods_list, $goods_id, $goods_num, $goods_spec, $user_id = 0, $goods_parent_id = 0, $goods_type = 0, $goods_data = array(), $goods_flag = '')
    {
        $orderLogic = new \Common\Logic\OrderLogic();

        return $orderLogic->addGoods($goods_list, $goods_id, $goods_num, $goods_spec, $user_id, $goods_parent_id, $goods_type, $goods_data, $goods_flag);
    }
}