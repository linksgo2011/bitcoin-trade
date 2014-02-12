<div class="block span12">
    <div class="block-heading" data-toggle="collapse" data-target="#tablewidget">管理员直接充扣账户金额</div>
    <div class="block-body collapse in">
    	<div class="row-fluid">
    		<div class="span8">
		    	<p>
		    		<div class="alert">管理员直接操作用户账户金额</div>
		    	</p>
		    	<?php 
		            $this->BForm->formatInput = '<div class="control-group"><label class="control-label">%s</label><div class="controls">%s</div></div>';
		    	 ?>
				<?php echo $this->BForm->create('Charge'); ?>
				<?php echo $this->BForm->input('account_type',array('label'=>'账户类型:','type'=>'select','options'=>$account_type_arr,'style'=>'width:200px;','after'=>'选择账户类型')); ?>
				<?php echo $this->BForm->input('mark',array('label'=>'备注:','default'=>"管理员直接操作",'type'=>'textarea','style'=>'width:200px;')); ?>
				<?php echo $this->BForm->input('amount',array('label'=>'数量','type'=>'text','style'=>'width:200px;','default'=>1,'after'=>'为负数为扣除')) ?>
				<?php echo $this->BForm->submit('提交'); ?>
				<?php echo $this->Form->end(); ?>
    		</div>
    		<div class="span4">
    			<h5>用户账户</h5>
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
    		</div>
    	</div>
    </div>
 </div>