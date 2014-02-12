<div class="container">
  <div class="row">
  	<div class="col-md-12">
  		<div class="widget">
			<div class="widget-header">
				<i class="icon-user"></i>
				<h3>申请充值</h3>
			</div> <!-- /widget-header -->
			<div class="widget-content" style="min-height:400px;">
				<div class="bs-example bs-example-tabs">
				  <ul id="myTab" class="nav nav-tabs">
				  	<?php foreach ($account_type_arr as $key => $value): ?>
				    	<li class="<?php echo ($account_type==$key)?'active':'' ?>"><a href="/Accounts/charge/<?php echo $key ?>"><?php echo $value; ?></a></li>
				  	<?php endforeach ?>
				  </ul>
				</div>
				<p></p>
				
				<?php if (in_array($account_type,array_keys($v_account_type_arr))): ?>
					<span >
						请将 <?php echo strtoupper($account_type); ?> 转入以下地址，系统将自动确认您的转账并为您充值,充值不得低于0.001,否则钱包可能无法收到。！
					</span>
					<div class="form-group" style="text-align:center;padding:20px;">
						<h4>
						充值地址：
							<span class="red"><?php echo $address?$address:'没有找到'; ?></span>
							<?php if (!$address): ?>
								<a href="/PurseAddresses/reset/<?php echo $account_type ?>" class="col-sm-6 pull-right">重新获取</a>
							<?php endif ?>
						</h4>
					</div>
					<?php else: ?>
					<div>
						<?php echo $merchant['Merchant']['charge_info'] ?>
					</div>
				<?php endif ?>
			</div> <!-- /widget-content -->
		</div> <!-- /widget -->	
    </div> <!-- /span6 -->
  </div> <!-- /row -->
</div> <!-- /container -->
