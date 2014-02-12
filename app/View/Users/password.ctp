<div class="container">
  <div class="row">
  	<div class="span12">
  		<div class="widget">
			<div class="widget-header">
				<i class="icon-user"></i>
				<h3>修改登录密码</h3>
			</div> <!-- /widget-header -->
			
			<div class="widget-content" style="min-height:400px;">
				<?php echo $this->BForm->create('User'); ?>
				<?php echo $this->BForm->input('password',array('label'=>'旧密码','type'=>'password','style'=>'width:200px;')) ?>
				<?php echo $this->BForm->input('newpassword',array('label'=>'新的密码','type'=>'password','style'=>'width:200px;')) ?>
				<?php echo $this->BForm->input('cpassword',array('label'=>'重复密码','type'=>'password','style'=>'width:200px;')); ?>
				<?php echo $this->BForm->submit('确定'); ?>
				<?php echo $this->Form->end(); ?>
			</div> <!-- /widget-content -->
		</div> <!-- /widget -->	
    </div> <!-- /span6 -->

  </div> <!-- /row -->

</div> <!-- /container -->
