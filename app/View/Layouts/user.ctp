<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo @$title_for_layout; ?></title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">    
    <?php echo $this->Html->css('bootstrap.min'); ?>
    <?php echo $this->Html->css('bootstrap-responsive.min'); ?>
    
    <!-- <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet"> -->
    <?php echo $this->Html->css('font-awesome.min'); ?>
    
    <?php echo $this->Html->css('ui-lightness/jquery-ui-1.10.0.custom.min'); ?>
    
    <?php echo $this->Html->css('base-admin-3'); ?>
    <?php echo $this->Html->css('base-admin-3-responsive'); ?>
    <?php echo $this->Html->css('pages/dashboard'); ?>
    <?php echo $this->Html->css('custom'); ?>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <?php echo $this->Html->script('libs/jquery-1.9.1.min'); ?>
    <?php echo $this->Html->script('libs/jquery-ui-1.10.0.custom.min'); ?>
    <?php echo $this->Html->script('libs/bootstrap.min'); ?>
    <?php echo $this->Html->script('dialog',array('id'=>'dialog_js')); ?>
    <?php echo $this->Html->script('Application'); ?>


  </head>

<body>

<nav class="navbar navbar-inverse" role="navigation">

	<div class="container">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
      <span class="sr-only">Toggle navigation</span>
      <i class="icon-cog"></i>
    </button>
    <a class="navbar-brand" href="/Users/home">比特币交易系统</a>
  </div>

  <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav navbar-right">
		<li class="dropdown">
			<a href="javscript:;" class="dropdown-toggle" data-toggle="dropdown">
				<i class="icon-user"></i> 
				<?php echo $user['name']; ?>
				<b class="caret"></b>
			</a>
			<ul class="dropdown-menu">
				<li><a href="/Users/home">个人中心</a></li>
				<li class="divider"></li>
				<li><a href="/Users/logout">退出</a></li>
			</ul>
		</li>
        <li><a href="/Accounts/index/">帐户资金</a></li>
        <li class="dropdown">
            <a href="javscript:;" class="dropdown-toggle" data-toggle="dropdown">
            帐户安全
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li><a href="/Users/password">修改密码</a></li>
                <li><a href="/Users/pay_password">修改支付密码</a></li>
                <li><a href="/Users/ga">google 2步验证</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="javscript:;" class="dropdown-toggle" data-toggle="dropdown">
            充值/提现
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li><a href="/Accounts/charge">充值</a></li>
                <li><a href="/Accounts/recharge">提现</a></li>
            </ul>
        </li>
        <li><a href="/ChargeCodes/index">充值码</a></li>
    </ul>
  </div><!-- /.navbar-collapse -->
</div> <!-- /.container -->
</nav>
    



    
<div class="subnavbar">

	<div class="subnavbar-inner">
	
		<div class="container">
			
			<a href="javascript:;" class="subnav-toggle" data-toggle="collapse" data-target=".subnav-collapse">
		      <span class="sr-only">切换导航</span>
		      <i class="icon-reorder"></i>
		    </a>

			<div class="collapse subnav-collapse">
				<ul class="mainnav">
					<li class="dropdown <?php echo ($this->action == 'home')?'active':'' ?>">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-home"></i>
							<span>市场</span>
                            <b class="caret"></b>
						</a>
                        <ul class="dropdown-menu">
                            <li><a href="/Users/home/ARS_BTC">ARS_BTC市场</a></li>
                            <li><a href="/Users/home/ARS_LTC">ARS_LTC市场</a></li>
                        </ul>	    				
					</li>
					<li class="dropdown">					
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-copy"></i>
							<span>交易记录</span>
							<b class="caret"></b>
						</a>	    
					
						<ul class="dropdown-menu">
                            <li><a href="/Trades/index/">全部</a></li>
                            <li><a href="/Trades/index?order_type=ARS_BTC">ARS_BTC</a></li>
                            <li><a href="/Trades/index?order_type=ARS_LTC">ARS_LTC</a></li>
							<!-- <li><a href="/Orders/index/?trade_type=buy">我的买单</a></li> -->
							<!-- <li><a href="/Orders/index/?trade_type=sell">我的卖单</a></li> -->
							<!-- <li><a href="/Orders/stat/">订单统计</a></li> -->
						</ul> 				
					</li>
				</ul>
			</div> <!-- /.subnav-collapse -->

		</div> <!-- /container -->
	
	</div> <!-- /subnavbar-inner -->

</div> <!-- /subnavbar -->
    
    
<div class="main">

<div class="container">
	<?php echo $this->Session->flash(); ?>
</div>

<?php echo $this->fetch('content'); ?>
    
</div> <!-- /main -->
    
<div class="footer">
		
	<div class="container">
		
		<div class="row">
			
			<div id="footer-copyright" class="col-md-6">
				&copy; 2012-13 bitcoin.
			</div> <!-- /span6 -->
		</div> <!-- /row -->
		
	</div> <!-- /container -->
	
</div> <!-- /footer -->
    <script type="text/javascript">
        // <a href="javascript:void(0);" ectype="dialog" dialog_id="my_address_edit" dialog_title="编辑地址" dialog_width="700" uri="index.php?app=my_address&act=edit&addr_id=2" class="edit1 float_none">编辑</a>
        function ajax_form( id, title, url, width ) {
            if ( !width ) {
                width = 400;
            }
            var d = DialogManager.create( id );
            d.setTitle( title );
            d.setContents( "ajax", url );
            d.setWidth( width );
            d.show( "center" );
            return d;
        }
        var take_ajax_form  = function () {
            var id = $(this).attr('dialog_id');
            var title = $(this).attr('dialog_title') ? $(this).attr('dialog_title') : '';
            var url = $(this).attr('uri');
            var width = $(this).attr('dialog_width');
            ajax_form(id, title, url, width);
            return false;
        }
        $(function() {
            $('*[ectype="dialog"]').click(function() {
                take_ajax_form.call(this);
            });
        })
    </script>
  </body>
</html>
