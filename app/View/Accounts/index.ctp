<div class="container">
  <div class="row">
  	<div class="span12">
  		<div class="widget">
			<div class="widget-header">
				<i class="icon-user"></i>
				<h3>查看账户信息</h3>
			</div> <!-- /widget-header -->
			
			<div class="widget-content" style="min-height:400px;">
				<table class="table">
					<tr>
						<th>币种账户</th>
						<th>账户</th>
						<th>冻结</th>
					</tr>
					<tr>
						<td>BTC</td>
						<th><?php echo $account['Account']['btc_balance']; ?></th>
						<th><?php echo $account['Account']['btc_balance_freeze']; ?></th>
					</tr>
					<tr>
						<td>LTC</td>
						<th><?php echo $account['Account']['ltc_balance']; ?></th>
						<th><?php echo $account['Account']['ltc_balance_freeze']; ?></th>
					</tr>
					<tr>
						<td>ARS</td>
						<th><?php echo $account['Account']['ars_balance']; ?></th>
						<th><?php echo $account['Account']['ars_balance_freeze']; ?></th>
					</tr>
<!-- 					<tr>
						<td>USD</td>
						<th><?php echo $account['Account']['usd_balance']; ?></th>
						<th><?php echo $account['Account']['usd_balance_freeze']; ?></th>
					</tr> -->
				</table>
			</div> <!-- /widget-content -->
		</div> <!-- /widget -->	
    </div> <!-- /span6 -->

  </div> <!-- /row -->

</div> <!-- /container -->
