<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="/jdDoctertest1/Public/css/test.css" />
    <script type="text/javascript" src="/jdDoctertest1/Public/js/jquery-3.2.1.js"></script>
</head>
<body>

<div class="bn_top">
    <div class="t_left" style="position:relative;">
        <a href="#">
            &nbsp;&nbsp;&lt;&lt;
        </a>
    </div>
    <div class="t_right">
        维修账单
    </div>
</div>

<div class="bn_center">
<div class="c_top">
    <div class="order-li-opt">
        <label>
            <input class="ipt" name="repair_type" type="radio" value="0" />保内订单
            <input class="ipt" name="repair_type" type="radio" value="1" />保外订单
        </label>
    </div>
</div>
<hr width="100%" style=" height:0.7rem; background-color:#D9D9D9; border:none;" />
</div>

<div class="m_1">
    <div class="m_1_aken">
        <div class="m_left_1">
            高&nbsp;&nbsp;空&nbsp;&nbsp;费
        </div>

        <div class="m_right_1">
            <label class="ax">请选择</label>
            <div class="lol">
                <img src="/jdDoctertest1/Public/images/2.png"/>
            </div>
        </div>
    </div>

    <div class="order-box"  style="display: none">
        <div class="order-div">
            <div class="order-ops line-x-b" >
                <ul class="clearfi">
                    <?php if(is_array($goods_parts_list)): $k = 0; $__LIST__ = $goods_parts_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$goods_parts): $mod = ($k % 2 );++$k; if($goods_parts['act_id'] == 高空费): ?><li class="clearfi line-x-t"  id="1">
                                <span class="goods_name"><?php echo ($goods_parts['goods_name']); ?></span>
                                <div data-node="li-ops" class="order-li-ops">
                            <span data-node="ctrl" data-cost="<?php echo ($goods_parts['sale_price']); ?>" data-type="-1" class="span-ops-1">
                                <input type="button" value="-"/>
                            </span>
                                    <span data-node="num" class="span-num">1</span>
                            <span data-node="ctrl"  data-cost="<?php echo ($goods_parts['sale_price']); ?>" data-type="1" class="span-ops-2">
                                <input type="button" value="+"/>
                            </span>
                                    <span data-node="cost"  data-cost="<?php echo ($goods_parts['sale_price']); ?>" class="span-cost">￥<?php echo ($goods_parts['sale_price']); ?></span>
                                    <input class="span-check"  data-check="check" type="checkbox" name="choice[<?php echo ($k); ?>]" value="0"  />
                                    <input type="hidden" data-event="num" name="num[<?php echo ($k); ?>]" value="1" />
                                    <input type="hidden" name="goods_id[<?php echo ($k); ?>]" value="<?php echo ($goods_parts['goods_id']); ?>" />
                                </div>
                            </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        </div>
        <input type="hidden" name="order_id" value="<?php echo ($order_id); ?>" />
    </div>
</div>
<hr width="100%" style=" height:0.9rem; background-color:#D9D9D9; border:none" />

<div class="m_1">
    <div class="m_1_aken">
        <div class="m_left_1">
            加&nbsp;&nbsp;雪&nbsp;&nbsp;种
        </div>

        <div class="m_right_1">
            <label class="ax">请选择</label>
            <div class="lol">
                <img src="/jdDoctertest1/Public/images/2.png"/>
            </div>
        </div>
    </div>

    <div class="order-box"  style="display: none">
        <div class="order-div">
            <div class="order-ops line-x-b" >
                <ul class="clearfi">
                    <?php if(is_array($goods_parts_list)): $k = 0; $__LIST__ = $goods_parts_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$goods_parts): $mod = ($k % 2 );++$k; if($goods_parts['act_id'] == 加雪种): ?><li class="clearfi line-x-t"  id="2">
                                <span class="goods_name"><?php echo ($goods_parts['goods_name']); ?></span>
                                <div data-node="li-ops" class="order-li-ops">
                            <span data-node="ctrl" data-cost="<?php echo ($goods_parts['sale_price']); ?>" data-type="-1" class="span-ops-1">
                                <input type="button" value="-"/>
                            </span>
                                    <span data-node="num" class="span-num">1</span>
                            <span data-node="ctrl"  data-cost="<?php echo ($goods_parts['sale_price']); ?>" data-type="1" class="span-ops-2">
                                <input type="button" value="+"/>
                            </span>
                                    <span data-node="cost"  data-cost="<?php echo ($goods_parts['sale_price']); ?>" class="span-cost">￥<?php echo ($goods_parts['sale_price']); ?></span>
                                    <input class="span-check"  data-check="check" type="checkbox" name="choice[<?php echo ($k); ?>]" value="0"  />
                                    <input type="hidden" data-event="num" name="num[<?php echo ($k); ?>]" value="1" />
                                    <input type="hidden" name="goods_id[<?php echo ($k); ?>]" value="<?php echo ($goods_parts['goods_id']); ?>" />
                                </div>
                            </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        </div>
        <input type="hidden" name="order_id" value="<?php echo ($order_id); ?>" />
    </div>
</div>
<hr width="100%" style=" height:0.9rem; background-color:#D9D9D9; border:none" />

<div class="m_1">
    <div class="m_1_aken">
        <div class="m_left_1">
            维修服务
        </div>

        <div class="m_right_1">
            <label class="ax">请选择</label>
            <div class="lol">
                <img src="/jdDoctertest1/Public/images/2.png"/>
            </div>
        </div>
    </div>

    <div class="order-box"  style="display: none">
        <div class="order-div">
            <div class="order-ops line-x-b" >
                <ul class="clearf">

                </ul>
            </div>
        </div>
        <input type="hidden" name="order_id" value="<?php echo ($order_id); ?>" />
    </div>
</div>

<hr width="100%" style=" height:0.9rem; background-color:#D9D9D9; border:none;" />


<div class="m_1">
    <div class="m_1_aken">
        <div class="m_left_1">
            配&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;件
        </div>

        <div class="m_right_1">
            <label class="ax">请选择</label>
            <div class="lol">
                <img src="/jdDoctertest1/Public/images/2.png"/>
            </div>
        </div>
    </div>

    <div  class="order-box"  style="display: none">
        <div class="order-div">
            <div class="order-ops line-x-b" >
                <ul class="clearfix">

                </ul>
            </div>
        </div>
        <input type="hidden" name="order_id" value="<?php echo ($order_id); ?>" />
    </div>
</div>

<hr width="100%" style=" height:0.9rem; background-color:#D9D9D9; border:none;" />

<div style="height:8.4rem; line-height:8.4rem; clear:both;"></div>

<div class="footer">
    <div class="order-btn">
        <div class="order-cost">
            合计：<span>0元</span>
        </div>
    </div>
    <div class="f_right">
        <button class="btn1" >取消</button>
        <button class="btn2" >确认</button>
    </div>
</div>

</body>
<script>
    $(".ipt").click(function(){
        var val_payPlatform = $('input[name="repair_type"]:checked ').val();
        $.ajax({
            type:"POST",
            dataType:"json",
            url:"/jdDoctertest1/index.php?s=/Admin/Test/test2",
            success:function(data){
                var json = eval(data);
                var params = [];
                $.each(json, function (index, item) {
                    //循环获取数据
                        var sex = json[index];
                        //将数据全部存入数组之中
                        params.push(
                               sex
                        )
                });
                var pt = $('.m_left_1').eq(2).text();
                console.log('pt:'+pt);
                if(val_payPlatform == 0){
                    switchItem();
                    $('li.clearfix').remove();
                    for(var i = 0; i<params.length; i++){
                        if(params[i].act_id== '配件'){
                            $('ul.clearfix').append( "<li class='clearfix line-x-t'>" +
                                    "<span class='goods_name'>"
                                    + params[i].goods_name + "" +
                                    "</span>" +
                                    "<div data-node='li-ops' class='order-li-ops'>" +
                                    "<span data-node='ctrl' data-cost='"+params[i].is_under_guarantee+"' data-type='-1' class='span-ops-1'> " +
                                    "<input type='button' value='-'/>" +
                                    "</span>" +
                                    "<span data-node='num' class='span-num'>1</span> "+
                                    "<span data-node='ctrl'  data-cost='"+params[i].is_under_guarantee+"' data-type='1' class='span-ops-2'> " +
                                    "<input type='button' value='+'/>"+
                                    "</span>"+
                                    "<span data-node='cost'  data-cost='"+params[i].is_under_guarantee+"' class='span-cost'>" +
                                    '￥'+params[i].is_under_guarantee+
                                    "</span>"+
                                    "<input class='span-check' data-check='check' type='checkbox' name='' value='0'  />"+
                                    "</div>"
                                    +"</li>");
                            if(params[i].is_under_guarantee == 0){
                                var cc = $('ul.clearfix span.span-cost');
                                cc.html('厂家结算');
                            }
                        }

                        if(params[i].act_id== '维修服务'){
                            $('ul.clearf').append( "<li class='clearfix line-x-t'>" +
                                    "<span class='goods_name'>"
                                    + params[i].goods_name + "" +
                                    "</span>" +
                                    "<div data-node='li-ops' class='order-li-ops'>" +
                                    "<span data-node='ctrl' data-cost='"+params[i].is_under_guarantee+"' data-type='-1' class='span-ops-1'> " +
                                    "<input type='button' value='-'/>" +
                                    "</span>" +
                                    "<span data-node='num' class='span-num'>1</span> "+
                                    "<span data-node='ctrl'  data-cost='"+params[i].is_under_guarantee+"' data-type='1' class='span-ops-2'> " +
                                    "<input type='button' value='+'/>"+
                                    "</span>"+
                                    "<span data-node='cost'  data-cost='"+params[i].is_under_guarantee+"' class='span-cost'>" +
                                    '￥'+params[i].is_under_guarantee+
                                    "</span>"+
                                    "<input class='span-check' data-check='check' type='checkbox' name='' value='0'  />"+
                                    "</div>"
                                    +"</li>");
                            if(params[i].is_under_guarantee == 0){
                                var cc = $('ul.clearf span.span-cost');
                                cc.html('厂家结算');
                            }
                        }
                    }
                }else {
                    switchItem();
                    $('li.clearfix').remove();
                    for(var i = 0; i<params.length; i++){
                        if(params[i].act_id== '配件') {
                            $('ul.clearfix').append("<li class='clearfix line-x-t'>" +
                                    "<span class='goods_name'>"
                                    + params[i].goods_name + "" +
                                    "</span>" +
                                    "<div data-node='li-ops' class='order-li-ops'>" +
                                    "<span data-node='ctrl' data-cost='" + params[i].sale_price + "' data-type='-1' class='span-ops-1'> " +
                                    "<input type='button' value='-'/>" +
                                    "</span>" +
                                    "<span data-node='num' class='span-num'>1</span> " +
                                    "<span data-node='ctrl'  data-cost='" + params[i].sale_price + "' data-type='1' class='span-ops-2'> " +
                                    "<input type='button' value='+'/>" +
                                    "</span>" +
                                    "<span data-node='cost'  data-cost='" + params[i].sale_price + "' class='span-cost'>" +
                                    '￥' + params[i].sale_price +
                                    "</span>" +
                                    "<input class='span-check' data-check='check' type='checkbox' name='' value='0'  />" +
                                    "</div>"
                                    + "</li>");
                        }

                        if(params[i].act_id== '维修服务') {
                            $('ul.clearf').append("<li class='clearfix line-x-t'>" +
                                    "<span class='goods_name'>"
                                    + params[i].goods_name + "" +
                                    "</span>" +
                                    "<div data-node='li-ops' class='order-li-ops'>" +
                                    "<span data-node='ctrl' data-cost='" + params[i].sale_price + "' data-type='-1' class='span-ops-1'> " +
                                    "<input type='button' value='-'/>" +
                                    "</span>" +
                                    "<span data-node='num' class='span-num'>1</span> " +
                                    "<span data-node='ctrl'  data-cost='" + params[i].sale_price + "' data-type='1' class='span-ops-2'> " +
                                    "<input type='button' value='+'/>" +
                                    "</span>" +
                                    "<span data-node='cost'  data-cost='" + params[i].sale_price + "' class='span-cost'>" +
                                    '￥' + params[i].sale_price +
                                    "</span>" +
                                    "<input class='span-check' data-check='check' type='checkbox' name='' value='0'  />" +
                                    "</div>"
                                    + "</li>");
                        }
                    }
                }
                test();
                get_goods_price();
            }
        });
    });
</script>
<script>
    function switchItem(){
        $('.ax').html('请选择');
        $('.span-num').html(1);
        var aa = $('li.clearfi');
        for(var i = 0; i<aa.length; i++){
            var bb=$('li.clearfi').find('.span-num').eq(i).text();
            var cc=$('li.clearfi').find('.span-ops-1').eq(i).data('cost');
            var dd=$('li.clearfi').find('.span-cost').eq(i).text();
            $('li.clearfi').find('.span-cost').eq(i).html('￥' + cc);
            console.log(dd);
            console.log(bb);
            console.log(cc);
        }
        $('.span-check').prop("checked",false);
        $('.span-cost').css('color','black');
        if( $('.order-box').css("display")=="block"){
            $('.order-box').hide();
        }else{
            $('.order-box').hide();
        }
    }

    var tat = $('.order-box');
    function test(){
        $(".span-check").click(function(){
            var txt = $(this).siblings(".span-cost");
            tat = $(this).parents(".order-box");
            if(this.checked){
                txt.css('color','red');
            }else {
                txt.css('color','black');
            }
        });

        $('[data-node="li-ops"]').on('click','.span-check',function() {
            get_goods_price();
            get_check_price();
        })
    }

    //更新当前div的合计价格
    function get_check_price(){
        var jshu = 0;
        var goods_price = 0; // 商品起始价
        console.log(tat);
        var attach_ops = tat.find('[data-node="li-ops"]');//获得当前显示数据内容div
        var txt = tat.prev('.m_1_aken').find('.ax');
        var span =  attach_ops.children('.span-cost').html();
        if(attach_ops.length > 0){
            //开启遍历
            $(attach_ops).each(function () {
                //获得当前价格变量
                var cost = parseFloat($(this).children('[data-node="cost"]').data('cost')),
                        selected = $(this).children('.span-check').prop('checked');//获得当前是否勾选了该商品
                //如果没有勾选
                if (selected==false) {
                    cost = 0;//返回cost为0
                }else{
                    ++jshu;
                }
                goods_price += cost;//更新价格
            })
        }

        if(jshu >= 1 ){
            if(span == '厂家结算'){
                txt.html('已选中'+jshu +'项，厂家结算'); // 变动价格显示
            }else {
                txt.html('已选中'+jshu +'项，合计' + goods_price+ '元'); // 变动价格显示
            }
        }else
        {
            txt.html("请选择");
        }
    }

    function get_goods_price() {
        var goods_price = 0; // 商品起始价
        var attach_ops =$('[data-node="li-ops"]');//显示数据内容div
        //如果有数据
        if (attach_ops.length > 0) {
            //开启遍历
            attach_ops.each(function () {
                //获得当前价格变量
                var cost = parseFloat($(this).children('[data-node="cost"]').data('cost')),
                        selected = $(this).children('.span-check').prop('checked');//获得当前是否勾选了该商品
                if (selected==false) {
                    cost = 0;//返回cost为0
                }
                goods_price += cost;
            })
        }
        $('.order-cost').html('合计<span>￥' + goods_price.toFixed(2) + '元</span>'); // 变动价格显示
    }


        $('.order-box').on('click', '[data-node="ctrl"]', function () {
            var parent = $(this).parent();//li父元素
            var type = $(this).data('type');//+-符号
            var cost = $(this).data('cost');//当前价格
            var num = parent.find('[data-node="num"]').text();//中间数字大小
            var val = parseInt(num) + parseInt(type);//计算加减符号后的中间数字大小
            if (val == 0) {
                return;
            }
            else {
                if(cost == 0){
                    parent.find('[data-event="num"]').val(val);//把加减后的中间数字 赋予给页面
                    parent.find('[data-node="num"]').html(val);//拿到加减后的中间数字
                }else {
                    parent.find('[data-event="num"]').val(val);//把加减后的中间数字 赋予给页面
                    parent.find('[data-node="num"]').html(val);//拿到加减后的中间数字
                    parent.find('[data-node="cost"]').data('cost', ((cost * 1000 * val) / 1000).toFixed(2)).html('￥' + ((cost * 1000 * val) / 1000).toFixed(2));//计算出加减后价格
                }
            }
            get_goods_price();
            get_check_price();
        });
</script>

<script>
    /* div层点击显示隐藏*/
    $('div.lol').on('click',function(){
        var val_payPlatform = $('input[name="repair_type"]:checked ').val();
        if (val_payPlatform==undefined){
            alert('请选择订单类型。');
            return false;
        }
        var ts = $(this).parents(".m_1_aken");
        var order = ts.next();
        if (order.css("display")=="none") {
            order.show();
        } else {
            order.hide();
        }
    })
</script>
</html>