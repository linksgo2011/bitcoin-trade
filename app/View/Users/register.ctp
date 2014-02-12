<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo @$title_for_layout; ?></title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">    
    <?php echo $this->Html->css('bootstrap.min'); ?>
    <?php echo $this->Html->css('bootstrap-responsive.min'); ?>
    
    <link href="http//fonts.googleapis.com/css?family=Open+Sans400italic,600italic,400,600" rel="stylesheet">
    <?php echo $this->Html->css('font-awesome.min'); ?>
    
    <?php echo $this->Html->css('ui-lightness/jquery-ui-1.10.0.custom.min'); ?>
    
    <?php echo $this->Html->css('base-admin-3'); ?>
    <?php echo $this->Html->css('base-admin-3-responsive'); ?>
    <?php echo $this->Html->css('pages/signin'); ?>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
<body>
	
<nav class="navbar navbar-inverse" role="navigation">

	<div class="container">

  <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav navbar-right">
      <li class="">						
			<a href="<?php echo $this->Html->url(array('action'=>'login')) ?>>">
				登录
			</a>			
		</li>

		<li class="">
						
			<a href="/">
				<i class="icon-chevron-left"></i>&nbsp;&nbsp; 
				返回首页
			</a>
			
		</li>
    </ul>
  </div><!-- /.navbar-collapse -->
</div> <!-- /.container -->
</nav>

<div class="account-container register stacked">
	<div class="content clearfix">
		<?php echo $this->Form->create('User'); ?>
			<h1>注册</h1>			
			<?php
			  $this->BForm->formatInput = '<div class="field">
			      <label>%s</label>
			     %s
			  </div>';
			?>
			<div class="login-fields">
				<p>*必填</p>
				<?php echo $this->BForm->input('name',array('label'=>'用户名*','type'=>'text','class'=>'form-control')); ?>
				<?php echo $this->BForm->input('email',array('label'=>'邮箱*','type'=>'text','class'=>'form-control')); ?>
				<?php echo $this->BForm->input('password',array('label'=>'密码*','type'=>'password','class'=>'form-control')); ?>
				<?php echo $this->BForm->input('cpassword',array('label'=>'确认密码*','type'=>'password','class'=>'form-control')); ?>
				<?php echo $this->BForm->input('pay_password',array('label'=>'支付密码*','type'=>'password','class'=>'form-control')); ?>
				<?php echo $this->BForm->input('qq',array('label'=>'qq','type'=>'text','class'=>'form-control')); ?>
				<?php echo $this->BForm->input('tel',array('label'=>'电话','type'=>'text','class'=>'form-control')); ?>
			</div> <!-- /login-fields -->
			<div class="login-actions">
				<button class="login-action btn btn-primary">注册</button>
			</div> <!-- .actions -->
			<?php echo $this->Form->end(); ?>
	</div> <!-- /content -->
	
</div> <!-- /account-container -->



</body>
</html>