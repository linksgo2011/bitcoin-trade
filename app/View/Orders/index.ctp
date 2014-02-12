<div class="container">
  <div class="row">
  	<div class="span12">
  		<div class="widget">
			<div class="widget-header">
				<i class="icon-user"></i>
				<?php $arr = array('buy'=>'买单','sell'=>'卖单'); ?>
				<h3>查看我的<?php echo $arr[$_GET['trade_type']]; ?></h3>
			</div> <!-- /widget-header -->
			<div class="widget-content">
				<p>
					<div class="well">
						说明:当前看到的为挂出的单子,系统会从相应的单子搜索进行交易,剩余的数量会不断减少
					</div>
				</p>
				<table class="table">
					<tr>
						<th>ID</th>
						<th>状态</th>
						<th>交易类型</th>
						<th>当前数量</th>
						<th>价格</th>
						<th>当前总价</th>
						<th>创建时间</th>
						<th>操作</th>
					</tr>
					<?php foreach ($data as $key => $one): ?>
						<tr>
							<td><?php echo $one['Order']['id']; ?></td>
							<td><?php echo $active_arr[$one['Order']['active']]; ?></td>
							<td><?php echo $one['Order']['order_type']; ?></td>
							<td><?php echo $one['Order']['number']; ?></td>
							<td><?php echo $one['Order']['price']; ?></td>
							<td><?php echo $one['Order']['amount_price']; ?></td>
							<td><?php echo date("Y-m-d H:i:s",$one['Order']['created']); ?></td>
							<td>
								<!-- Todo 如果成交,每个单子会产生订单明细 -->
								<a href="javascript:void(0);" ectype="dialog" dialog_id="view_trades" dialog_title="成交明细" dialog_width="700" uri="/Trades/index/<?php echo $one['Order']['id'] ?>" >成交明细</a>
								<?php if ($one['Order']['active'] == 0): ?>
									<?php echo $this->Html->link('作废此单',array('action'=>'invalid',$one['Order']['id']),array(),'是否作废此单?'); ?>
								<?php endif ?>
							</td>	
						</tr>
					<?php endforeach ?>
				</table>
				<?php echo $this->element("pages"); ?>
			</div> <!-- /widget-content -->
		</div> <!-- /widget -->	
    </div> <!-- /span6 -->

  </div> <!-- /row -->

</div> <!-- /container -->