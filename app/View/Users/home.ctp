<?php 
    $t_acount_type = $this->Html->splitOrderType($order_type,0);
	$v_acount_type = $this->Html->splitOrderType($order_type,1);
 ?>
 <style type="text/css">
	.limit_20{
		height:300px;
		overflow:auto;
	}
    .submit{
        text-align:center;
    }
    .text input{
        border:1px solid #ccc;
        padding:5px;
    }
 </style>
<div class="container">	
    <div class="row-fluid">
        <div class="col-md-8">

          <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="widget stacked">
                    <div class="widget-header">
                        <i class="icon-star"></i>
                        <h3>价格走势</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content" style="min-height:300px;">
                        <!-- TODO  -->
                        <div id="line-chart">
                            
                        </div>
                    </div> <!-- /widget-content -->
                </div> <!-- /widget -->         

            </div> <!-- /span6 -->
          </div> <!-- /row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="widget widget-nopad stacked">
                        <div class="widget-header">
                            <i class="icon-list-alt"></i>
                            <h3>买入<?php echo $v_acount_type; ?></h3>
                        </div> <!-- /widget-header -->
                        <div class="widget-content " style="padding:10px;">
                            <?php echo $this->Form->create('Order',array('url'=>'/Orders/buy/'.$order_type)); ?>
                            <?php echo $this->Form->input('order_type',array('label'=>false,'div'=>false,'type'=>'hidden','value'=>$order_type)) ?>
                            <table class="table">
                                <tr>
                                    <th>最佳买入价格</th>
                                    <td><?php echo $stata['min_price'] ?></td>
                                </tr>
                                <tr>
                                    <th>可用余额</th>
                                    <td class="red"><?php echo $account['Account']['ars_balance']; ?>(<?php echo $t_acount_type ?>)</td>
                                </tr>
                                <tr>
                                    <th>买入价格</th>
                                    <td>
                                        <?php echo $this->Form->input('price',array('label'=>false,'type'=>'text','style'=>'width:100px;','default'=>'1','class'=>'price')); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>买入数量</th>
                                    <td>
                                        <?php echo $this->Form->input('number',array('label'=>false,'type'=>'text','style'=>'width:100px;','default'=>'1','class'=>'number')); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>兑换额</th>
                                    <td>
                                        <?php echo $this->Form->input('amount_price',array('label'=>false,'type'=>'text','style'=>'width:100px;','disabled '=>'disabled ','class'=>'amount_price')); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>手续费</th>
                                    <td>
                                        <?php echo ($rate*100).'%'; ?>
                                    </td>
                                </tr>
                            </table>
                            <?php echo $this->Form->submit('确定',array('class'=>'btn btn-primary')); ?>
                            <?php echo $this->Form->end(); ?>
                        </div> <!-- /widget-content -->
                    </div> <!-- /widget --> 
                </div>
                <div class="col-md-6">
                    <div class="widget widget-nopad stacked">
                        <div class="widget-header">
                            <i class="icon-list-alt"></i>
                            <h3>卖出<?php echo $v_acount_type; ?></h3>
                        </div> <!-- /widget-header -->
                        <div class="widget-content " style="padding:10px;">
                            <?php echo $this->Form->create('Order',array('url'=>'/Orders/sell/'.$order_type)); ?>
                            <?php echo $this->Form->input('order_type',array('label'=>false,'div'=>false,'type'=>'hidden','value'=>$order_type)) ?>
                            <table class="table">
                                <tr>
                                    <th>最佳卖出价格</th>
                                    <td><?php echo $stata['max_price'] ?></td>
                                </tr>
                                <tr>
                                    <th>可用余额</th>
                                    <td class="red"><?php echo $account['Account'][strtolower($v_acount_type).'_balance']; ?>(<?php echo $v_acount_type; ?>)</td>
                                </tr>
                                <tr>
                                    <th>卖出价格</th>
                                    <td>
                                        <?php echo $this->Form->input('price',array('label'=>false,'type'=>'text','style'=>'width:100px;','default'=>'1','class'=>"price")); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>卖出数量</th>
                                    <td>
                                        <?php echo $this->Form->input('number',array('label'=>false,'type'=>'text','style'=>'width:100px;','default'=>'1')); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>兑换额</th>
                                    <td>
                                        <?php echo $this->Form->input('amount_price',array('label'=>false,'type'=>'text','style'=>'width:100px;','disabled '=>'disabled ')); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>手续费</th>
                                    <td>
                                        <?php echo ($rate*100).'%'; ?>
                                    </td>
                                </tr>
                            </table>
                            <?php echo $this->Form->submit('确定',array('class'=>'btn btn-primary')); ?>
                            <?php echo $this->Form->end(); ?>
                        </div> <!-- /widget-content -->
                    </div> <!-- /widget --> 
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="widget widget-nopad stacked">
                        <div class="widget-header">
                            <i class="icon-list-alt"></i>
                            <h3>买单 <?php echo $v_acount_type; ?>-<?php echo $t_acount_type; ?></h3>
                        </div> <!-- /widget-header -->
                        <div class="widget-content limit_20">
                            
                            <table class="table">
                                <?php if ($buy_orders): ?>
                                    <tr>
                                        <th>买入价 (<?php echo $v_acount_type; ?>)</th>
                                        <th>买入量 (<?php echo $t_acount_type; ?>)</th>
                                        <th>兑换额 (<?php echo $t_acount_type; ?>)</th>
                                    </tr>
                                    <?php foreach ($buy_orders as $key => $one): ?>
                                        <tr>
                                            <td><?php echo $one['Order']['price']; ?></td>
                                            <td><?php echo $one['Order']['number']; ?></td>
                                            <td><?php echo $one['Order']['amount_price']; ?></td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </table>
                        </div> <!-- /widget-content -->
                    </div> <!-- /widget --> 
                </div>
                <div class="col-md-6">
                    <div class="widget widget-nopad stacked">
                                
                        <div class="widget-header">
                            <i class="icon-list-alt"></i>
                            <h3>卖单 <?php echo $t_acount_type; ?>-<?php echo $v_acount_type; ?></h3>
                        </div> <!-- /widget-header -->
                        <div class="widget-content limit_20">
                            
                            <table class="table">
                                <?php if ($sell_orders): ?>
                                    <tr>
                                        <th>卖出价 (<?php echo $v_acount_type; ?>)</th>
                                        <th>买入量 (<?php echo $t_acount_type; ?>)</th>
                                        <th>兑换额 (<?php echo $t_acount_type; ?>)</th>
                                    </tr>
                                    <?php foreach ($sell_orders as $key => $one): ?>
                                        <tr>
                                            <td><?php echo $one['Order']['price']; ?></td>
                                            <td><?php echo $one['Order']['number']; ?></td>
                                            <td><?php echo $one['Order']['amount_price']; ?></td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php else: ?>
                                    没有数据
                                <?php endif ?>
                            </table>
                        </div> <!-- /widget-content -->
                    </div> <!-- /widget --> 
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget widget-nopad stacked">
                                
                        <div class="widget-header">
                            <i class="icon-list-alt"></i>
                            <h3>交易记录</h3>
                        </div> <!-- /widget-header -->
                        <div class="widget-content limit_20" >
                            <table class="table">
                                <tr>
                                    <th>时间</th>
                                    <th>类型</th>
                                    <th>交易价</th>
                                    <th>成交量(<?php echo $v_acount_type; ?>)</th>
                                    <th>成交额(<?php echo $t_acount_type; ?>)</th>
                                </tr>
                                <?php if ($last_trades): ?>
                                    <?php foreach ($last_trades as $key => $one): ?>
                                    <tr class="<?php echo $one['Order']['trade_type']=='buy'?'bg_green':'bg_red'; ?>">
                                        <td><?php echo date('Y-m-d',$one['Trade']['created']); ?></td>
                                        <td class="<?php echo $one['Order']['trade_type']=='buy'?'green':'red'; ?>"><?php echo $trade_type_arr[$one['Order']['trade_type']]; ?></td>
                                        <td><?php echo $one['Trade']['price']; ?></td>
                                        <td><?php echo $one['Trade']['number']; ?></td>
                                        <td><?php echo $one['Trade']['amount']; ?></td>
                                    </tr>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="widget stacked">
                <div class="widget-header">
                    <i class="icon-bookmark"></i>
                    <h3>公告</h3>
                </div> <!-- /widget-header -->
                <div class="widget-content">
                    <?php echo $merchant['Merchant']['site_pub'] ?>
                </div> <!-- /widget-content -->
            </div> <!-- /widget -->
            <div class="widget stacked">
                <div class="widget-header">
                    <i class="icon-bookmark"></i>
                    <h3>账户资料</h3>
                </div> <!-- /widget-header -->
                <div class="widget-content">
                    <div class="ajaxload" uri="/Accounts/index/">
                        <table class="table">
                            <tr>
                                <th>币种账户</th>
                                <th>账户</th>
                                <th>冻结</th>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">BTC</td>
                                <th class="green"><?php echo $account['Account']['btc_balance']; ?></th>
                                <th class="red"><?php echo $account['Account']['btc_balance_freeze']; ?></th>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">LTC</td>
                                <th class="green"><?php echo $account['Account']['ltc_balance']; ?></th>
                                <th class="red"><?php echo $account['Account']['ltc_balance_freeze']; ?></th>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">ARS</td>
                                <th class="green"><?php echo $account['Account']['ars_balance']; ?></th>
                                <th class="red"><?php echo $account['Account']['ars_balance_freeze']; ?></th>
                            </tr>
                        </table>
                    </div>
                </div> <!-- /widget-content -->
            </div> <!-- /widget -->
            <div class="widget widget-nopad stacked">
                <div class="widget-header">
                    <i class="icon-list-alt"></i>
                    <h3>我的 <?php echo $order_type; ?>挂单</h3>
                </div> <!-- /widget-header -->
                <div class="widget-content limit_20" >
                    <table class="table">
                        <tr>
                            <th>类型</th>
                            <th>交易价</th>
                            <th>挂单量</th>
                            <th>操作</th>
                        </tr>
                        <?php if ($my_orders): ?>
                            <?php foreach ($my_orders as $key => $one): ?>
                            <tr class="<?php echo $one['Order']['trade_type']=='buy'?'bg_green':'bg_red'; ?>">
                                <td class="<?php echo $one['Order']['trade_type']=='buy'?'green':'red'; ?>"><?php echo $trade_type_arr[$one['Order']['trade_type']]; ?></td>
                                <td><?php echo $one['Order']['price']; ?></td>
                                <td><?php echo $one['Order']['number']; ?></td>
                                <td>
                                    <?php echo $this->Html->link('作废',array('controller'=>'Orders','action'=>'invalid',$one['Order']['id']),array(),'是否作废?'); ?>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        <?php endif ?>
                    </table>
                </div>
            </div>
        </div>
    </div>


</div>


<?php echo $this->Html->script('admin/highstock'); ?>
<script type="text/javascript">
var statistic = <?php echo $statistic ? json_encode($statistic) : '[]'; ?>;
var order_type = "<?php echo $order_type; ?>";
$(function(){

    $("input[name='data[Order][price]'],input[name='data[Order][number]']").blur(function(event) {
        var $form = $(this).parents("form");
        var price = $form.find("input[name='data[Order][price]']").val();
        var number = $form.find("input[name='data[Order][number]']").val();
        var amount_price = (price*number).toFixed(6);
        $form.find("input[name='data[Order][amount_price]']").val(amount_price);
    });

    $('#line-chart').highcharts('StockChart', {
        rangeSelector : {
            selected : 1
        },
        title : {
            text : false
        },
        rangeSelector:false,
        xAxis: {
            dateTimeLabelFormats: {
                second: '%Y-%m-%d<br/>%H:%M:%S',
                minute: '%Y-%m-%d<br/>%H:%M',
                hour: '%Y-%m-%d<br/>%H:%M',
                day: '%Y<br/>%m-%d',
                week: '%Y<br/>%m-%d',
                month: '%Y-%m',
                year: '%Y'
            }
        },
        series : [{
            name : '成交价格',
            data : statistic,

        }],
        tooltip: {
            animation:false,
            dateTimeLabelFormats: {
                second: '%Y-%m-%d<br/>%H:%M:%S',
                minute: '%Y-%m-%d<br/>%H:%M',
                hour: '%Y-%m-%d<br/>%H:%M',
                day: '%Y<br/>%m-%d',
                week: '%Y<br/>%m-%d',
                month: '%Y-%m',
                year: '%Y'
            },
            formatter:function(){
                var s = '<b>' + Highcharts.dateFormat('%m-%d', this.x) + '</b>'
                + '<br /><b>'+'成交价格'+'  '+this.y+'('+order_type+')'+'</b>';
                return s;
            }
        }
    });
});
</script>