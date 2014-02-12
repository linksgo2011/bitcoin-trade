<div class="container">
  <div class="row">
  	<div class="col-md-7">
  		<div class="widget">
			<div class="widget-header">
				<i class="icon-user"></i>
				<h3>申请提现</h3>
			</div> <!-- /widget-header -->
			<div class="widget-content">
				<div class="bs-example bs-example-tabs">
				  <ul id="myTab" class="nav nav-tabs">
				  	<?php foreach ($account_type_arr as $key => $value): ?>
				    	<li class="<?php echo ($account_type==$key)?'active':'' ?>"><a href="/Accounts/recharge/<?php echo $key ?>"><?php echo $value; ?></a></li>
				  	<?php endforeach ?>
				  </ul>
				</div>

				<?php if ($user['active'] <= -1): ?>
					<p class="alert alert-info">用户已经被锁定,无法操作!</p>
					<?php else: ?>
					<?php if (in_array($account_type,array_keys($v_account_type_arr))): ?>
						<?php echo $this->BForm->create('Charge'); ?>
						<p></p>
						<div class="form-group">
							<label class="col-sm-2 control-label"></label>
							<div class="col-sm-10 red">
								<?php echo strtoupper($account_type); ?>可用余额:
								<?php echo $account['Account'][$account_type.'_balance']; ?>
							</div>
						</div>
						<?php echo $this->Form->input('account_type',array('label'=>false,'type'=>'hidden','value'=>$account_type,'style'=>'width:200px;')); ?>
						<?php echo $this->BForm->input('amount',array('label'=>'提现金额:','type'=>'text','style'=>'width:200px;')); ?>
						<?php echo $this->BForm->input('address',array('label'=>'提现地址:','type'=>'text','style'=>'width:400px;','after'=>'需要给管理员操作的地址')); ?>
						<?php echo $this->BForm->input('pay_password',array('label'=>'支付密码','type'=>'password','style'=>'width:200px;')) ?>
						<?php echo $this->BForm->submit('提交'); ?>
						<?php echo $this->Form->end(); ?>
					<?php else: ?>
						<?php echo $merchant['Merchant']['charge_info'] ?>
					<?php endif; ?>
				<?php endif ?>
			</div> <!-- /widget-content -->
		</div> <!-- /widget -->	
    </div> <!-- /span6 -->
    <div class="col-md-5">
    	<div class="widget">
			<div class="widget-header">
				<i class="icon-list"></i>
				<h3>提现记录</h3>
			</div> <!-- /widget-header -->
			<div class="widget-content">
		    	<table class="table">
		    		<tr>
		    			<th>ID</th>
		    			<th>类型</th>
		    			<th>状态</th>
		    			<th>数量</th>
		    			<th>错误备注</th>
		    			<th>时间</th>
		    		</tr>
		    		<?php foreach ((array)$data as $key => $one): ?>
		    			<tr>
		    				<td><?php echo $one['Charge']['id']; ?></td>
		    				<td><?php echo $one['Charge']['account_type']; ?></td>
		    				<td><?php echo $active_arr[$one['Charge']['active']] ?></td>
		    				<td><?php echo $one['Charge']['amount']; ?></td>
		    				<td><?php echo $one['Charge']['error_mark']?$one['Charge']['error_mark']:'-'; ?></td>
		    				<td><?php echo date('Y-m-d H:i:s',$one['Charge']['created']); ?></td>
		    			</tr>
		    		<?php endforeach ?>
		    	</table>
		    	<?php echo $this->element('pages'); ?>
			</div>
		</div>
    </div>
  </div> <!-- /row -->
</div> <!-- /container -->