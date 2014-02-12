<!-- <div class="stats">
    <p class="stat"><span class="number">53</span>充值</p>
    <p class="stat"><span class="number">27</span>提现</p>
    <p class="stat pull-left">等待处理</p>
</div> -->
<div class="row-fluid">
    <div class="block">
        <p class="block-heading" data-toggle="collapse" data-target="#chart-container">每日交易量统计</p>
        <div id="chart-container" class="block-body collapse in">
            <div id="line-chart"></div>
        </div>
    </div>
</div>
<div class="ro-fluid">
    <div class="block">
        <p class="block-heading" data-toggle="collapse" data-target="#chart-container">快捷操作</p>
        <div id="chart-container" class="block-body collapse in" style="padding:20px;">
            <a href="/admin/Orders/clear" class="btn btn-primary" onclick="return confirm('删除后不可恢复,是否删除?')">删除所有的订单</a>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="block span6">
        <div class="block-heading" data-toggle="collapse" data-target="#tablewidget">最近注册</div>
        <div id="tablewidget" class="block-body collapse in" >
            <table class="table" style="min-height:200px;">
                <thead>
                    <tr>
                        <th>邮箱</th>
                        <th>用户名</th>
                        <th>注册时间</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($last_users): ?>
                        <?php foreach ($last_users as $key => $one): ?>
                            <tr>
                                <td><?php echo $one['User']['email']; ?></td>
                                <td><?php echo $one['User']['name']; ?></td>
                                <td><?php echo date('Y-m-d H:i:s',$one['User']['created']); ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
            <p><a href="/admin/Users/index">点击更多....</a></p>
        </div>
    </div>
    <div class="block span6">
        <div class="block-heading" data-toggle="collapse" data-target="#widget1container">全站用户账户统计</div>
        <div id="widget1container" class="block-body collapse in">
            <table class="table">
                <tr>
                    <th></th>
                    <th>BTC</th>
                    <th>LTC</th>
                    <th>ARS</th>
                </tr>
                <tr>
                    <th>用户账户</th>
                    <td class="red"><?php echo $account_stat['btc_balance']; ?></td>
                    <td class="red"><?php echo $account_stat['ltc_balance']; ?></td>
                    <td class="red"><?php echo $account_stat['ars_balance']; ?></td>
                </tr>
                <tr>
                    <th>冻结充值码</th>
                    <td class="red"><?php echo $account_stat['btc_balance_freeze']; ?></td>
                    <td class="red"><?php echo $account_stat['ltc_balance_freeze']; ?></td>
                    <td class="red"><?php echo $account_stat['ars_balance_freeze']; ?></td>
                </tr>
                <tr>
                    <th>待用充值码</th>
                    <td class="red"><?php echo $charge_code_stat['btc']; ?></td>
                    <td class="red"><?php echo $charge_code_stat['ltc']; ?></td>
                    <td class="red"><?php echo $charge_code_stat['ars']; ?></td>
                </tr>
                <tr>
                    <th>总计</th>
                    <td class="red"><?php echo $account_stat['btc_balance']+$account_stat['btc_balance_freeze']+$charge_code_stat['btc']; ?></td>
                    <td class="red"><?php echo $account_stat['ltc_balance']+$account_stat['ltc_balance_freeze']+$charge_code_stat['ltc']; ?></td>
                    <td class="red"><?php echo $account_stat['ars_balance']+$account_stat['ars_balance_freeze']+$charge_code_stat['ars']; ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="block span6">
        <div class="block-heading" data-toggle="collapse" data-target="#widget2container">最近交易<span class="label label-warning">+<?php echo count($last_orders); ?></span></div>
        <div id="widget2container" class="block-body collapse in">
            <table class="table" style="min-height:200px;">
                <tbody>
                    <tr>
                        <th>用户</th>
                        <th>类型</th>
                        <th>金额</th>
                        <th>日期</th>
                    </tr>
                    <?php if ($last_orders): ?>
                        <?php foreach ($last_orders as $key => $one): ?>
                            <tr>
                                <td>
                                    <p><?php echo $one['User']['name']; ?></p>
                                </td>
                                <td>
                                    <p><?php echo $one['Order']['order_type'];?></p>
                                </td>
                                <td>
                                    <p><?php echo $one['Order']['amount_price'];?></p>
                                </td>
                                <td>
                                    <p><?php echo date('Y-m-d',$one['Order']['created']); ?></p>
                                </td>
                            </tr>
                        <?php endforeach ?> 
                    <?php endif ?>  
                </tbody>
            </table>
            <p><a href="/admin/Orders/index?trade_type=buy">点击更多....</a></p>
        </div>
    </div>
    <div class="block span6">
        <p class="block-heading">系统收益(系统账户金额)</p>
        <div class="block-body">
            <table class="table">
                <tr>
                    <th>BTC</th>
                    <td><?php echo $sys_user_account['Account']['btc_balance']; ?></td>
                </tr>
                <tr>
                    <th>LTC</th>
                    <td><?php echo $sys_user_account['Account']['ltc_balance']; ?></td>
                </tr>
<!--                 <tr>
                    <th>USD</th>
                    <td><?php echo $sys_user_account['Account']['usd_balance']; ?></td>
                </tr> -->
                <tr>
                    <th>ARS</th>
                    <td><?php echo $sys_user_account['Account']['ars_balance']; ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<?php echo $this->Html->script('admin/highstock'); ?>
<?php echo $this->Html->script('admin/highcharts'); ?>
<script type="text/javascript">
var statistic = <?php echo $statistic ? json_encode($statistic) : '[]'; ?>;
$(function(){
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
            name : '交易量',
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
                + '<br /><b>'+'交易量'+'  '+this.y+'</b>';
                return s;
            }
        }
    });
});
</script>
