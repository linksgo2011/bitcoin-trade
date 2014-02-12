<div class="container">
  <div class="row">
  	<div class="span12">
  		<div class="widget">
			<div class="widget-header">
				<i class="icon-user"></i>
				<h3>修改支付密码</h3>
			</div> <!-- /widget-header -->
			
			<div class="widget-content" style="min-height:400px;">
				<?php echo $this->BForm->create('User'); ?>
				<?php echo $this->BForm->input('paypassword',array('label'=>'旧支付密码','type'=>'password','style'=>'width:200px;')) ?>
				<?php echo $this->BForm->input('newpaypassword',array('label'=>'新的支付密码','type'=>'password','style'=>'width:200px;')) ?>
				<?php echo $this->BForm->input('cpassword',array('label'=>'重复支付密码','type'=>'password','style'=>'width:200px;')); ?>
				<?php echo $this->BForm->submit('确定'); ?>
				<?php echo $this->Form->end(); ?>
			</div> <!-- /widget-content -->
		</div> <!-- /widget -->	
    </div> <!-- /span6 -->

  </div> <!-- /row -->

</div> <!-- /container -->
