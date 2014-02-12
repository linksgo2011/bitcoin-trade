<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo @$title_for_layout; ?></title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">    
    <?php echo $this->Html->css('bootstrap.min'); ?>
    <?php echo $this->Html->css('bootstrap-responsive.min'); ?>
    
    <?php echo $this->Html->css('font-awesome.min'); ?>
    
    <?php echo $this->Html->css('ui-lightness/jquery-ui-1.10.0.custom.min'); ?>
    
    <?php echo $this->Html->css('base-admin-3'); ?>
    <?php echo $this->Html->css('base-admin-3-responsive'); ?>
    <?php echo $this->Html->css('pages/signin'); ?>
    <?php echo $this->Html->script('libs/jquery-1.9.1.min'); ?>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
<body>
	
<nav class="navbar navbar-inverse" role="navigation">

	<div class="container">

  <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav navbar-right">
      <li class="">						
			<a href="<?php echo $this->Html->url(array('action'=>'register')) ?>">
				注册
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

<div class="account-container stacked">
	
	<div class="content clearfix">
		
		<?php echo $this->Form->create('User'); ?>
			<?php echo $this->Session->flash(); ?>
			<h1>登录系统</h1>		
             <?php
                  $this->BForm->formatInput = '<div class="field">
                      <label>%s</label>
                     %s
                  </div>';
              ?>
			<div class="login-fields">
				
				<p>登录你的账户:</p>
				<?php echo $this->BForm->input('email',array('label'=>'邮箱','id'=>'email','type'=>'text','class'=>'form-control input-lg username-field')); ?>
                <?php echo $this->BForm->input('password',array('label'=>'密码','type'=>'password','class'=>'form-control input-lg password-field')); ?>
			     <div class="ga" id="ga_password">
                    <?php echo $this->BForm->input('ga_password',array('label'=>'google authenticator 此密码','type'=>'text','class'=>'form-control input-lg password-field')); ?>
                 </div>
			</div> <!-- /login-fields -->
			
			<div class="login-actions">
				<span class="login-checkbox">
                    <?php $default = $this->request->data['User']['use_ga']; ?>
					<input id="use_ga" name="data[User][use_ga]" type="checkbox" <?php echo $default?'checked':''; ?> class="field login-checkbox" tabindex="4" />
					<label class="choice" for="Field">我开启了google authenticator</label>
				</span>
				<button class="login-action btn btn-primary">登录</button>
			</div> <!-- .actions -->
			
			<!-- Text Under Box -->
			<div class="login-extra">
				没有账号? <a href="<?php echo $this->Html->url(array('action'=>'register')) ?>">注册</a><br/>
					忘记密码?
				<a href="<?php echo $this->Html->url(array('action'=>'forget_password')) ?>">找回密码</a>
			</div> <!-- /login-extra -->
			<?php echo $this->Form->end(); ?>
	</div> <!-- /content -->
	
</div> <!-- /account-container -->

<script type="text/javascript">
    $("#use_ga").change(function(event) {
        if($(this).prop('checked')){
            $("#ga_password").show();
        }else{
            $("#ga_password").hide();
        }
    }).change();
</script>
</body>
</html>