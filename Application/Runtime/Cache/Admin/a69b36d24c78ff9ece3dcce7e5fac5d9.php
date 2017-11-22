<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ajax测试</title>
    <link rel="stylesheet" href="/jdDoctertest1/Public/css/test.css" />
    <script type="text/javascript" src="/jdDoctertest1/Public/js/jquery-3.2.1.js"></script>
</head>
<body>
<div class="bn_center">
    <div class="c_top">
        <div class="order-li-ops">
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
                <ul class="clearfix">

                </ul>
            </div>
        </div>
        <input type="hidden" name="order_id" value="<?php echo ($order_id); ?>" />
    </div>
</div>

<hr width="100%" style=" height:0.7rem; background-color:#D9D9D9; border:none;" />

<div class="footer">
    <div class="order-btn">
        <div class="order-cost">
            合计<span>0元</span>
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
                url:"/jdDoctertest1/index.php?s=/Admin/Test/test",
                type:'POST',
                dataType:"JSON",
                success:function(data){
                    console.log(val_payPlatform);
                   if (val_payPlatform == 1){
                        $('li').remove();
                        for(var i = 0; i<data.length; i++){
                            if(data[i].cit_id == 5){
                                //console.log(data[i].cit_id + data[i].goods_name + data[i].sale_price);
                                $('ul.clearfix').append( "<li class='clearfix line-x-t'>" +
                                        "<span class='goods_name'>"
                                            + data[i].goods_name + "" +
                                        "</span>" +
                                        "<div data-node='li-ops' class='order-li-ops'>" +
                                            "<span data-node='ctrl' data-cost='"+data[i].sale_price+"' data-type='-1' class='span-ops-1'> " +
                                                "<input type='button' value='-'/>" +
                                            "</span>" +
                                            "<span data-node='num' class='span-num'>1</span> "+
                                            "<span data-node='ctrl'  data-cost='"+data[i].sale_price+"' data-type='1' class='span-ops-2'> " +
                                                "<input type='button' value='+'/>"+
                                            "</span>"+
                                            "<span data-node='cost'  data-cost='"+data[i].sale_price+"' class='span-cost'>" +
                                                '￥'+data[i].sale_price+
                                            "</span>"+
                                            "<input class='span-check' data-check='check' type='checkbox' name='' value='0'  />"+
                                        "</div>"
                                        +"</li>");
                            }
                        }
                       test();
                       test2();
                       test3();
                       get_goods_price();
                    }else {
                        $('li').remove();
                        for(var i = 0; i<data.length; i++){
                            if(data[i].cit_id == 5){
                                //console.log(data[i].cit_id + data[i].goods_name + data[i].sale_price);
                                $('ul.clearfix').append( "<li class='clearfix line-x-t'>" +
                                        "<span class='goods_name'>"
                                        + data[i].goods_name + "" +
                                        "</span>" +
                                        "<div data-node='li-ops' class='order-li-ops'>" +
                                        "<span data-node='ctrl' data-cost='' data-type='-1' class='span-ops-1'> " +
                                        "<input type='button' value='-'/>" +
                                        "</span>" +
                                        "<span data-node='num' class='span-num'>1</span> "+
                                        "<span data-node='ctrl'  data-cost='' data-type='1' class='span-ops-2'> " +
                                        "<input type='button' value='+'/>"+
                                        "</span>"+
                                        "<span data-node='cost'  data-cost='"+data[i].cit_id+"' class='span-cost'>" +
                                        data[i].cit_id+
                                        "</span>"+
                                        "<input class='span-check' data-check='check' type='checkbox' name='' value='0'  />"+
                                        "</div>"
                                        +"</li>");
                            }
                            test();
                            test2();
                            get_goods_price();
                        }
                    }
                }
            });
        });

        function test(){
            $(".span-check").click(function(){
                var txt = $(this).siblings(".span-cost");
                if(this.checked){
                    txt.css('color','red');
                }else {
                    txt.css('color','black');
                }
            });
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

        function test2(){
            $('[data-node="li-ops"]').on('click','.span-check',function() {
                get_goods_price();
            })
        }

        function  test3(){
            $('[data-node="li-ops"]').on('click','[data-node="ctrl"]',function(){
                var parent=$(this).parent();//li父元素
                var type=$(this).data('type');//+-符号
                var cost=$(this).data('cost');//当前价格
                var num=parent.find('[data-event="num"]').val();//中间数字大小
                var val=parseInt(num)+parseInt(type);//计算加减符号后的中间数字大小
                console.log(parent);
                console.log(type);
                console.log(cost);
                console.log(num);
                console.log(val);

            });
        }

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