<div class="container">
  <div class="row">
  	<div class="span12">
  		<div class="widget">
			<div class="widget-header">
				<i class="icon-user"></i>
				<h3>提交买单(买入<?php echo $this->Html->splitOrderType($order_type,1); ?>)</h3>
			</div> <!-- /widget-header -->
			
			<div class="widget-content">
				<?php echo $this->BForm->create('Order'); ?>
                <?php echo $this->BForm->input('order_type_label',array('label'=>'当前市场','disabled'=>'disabled','value'=>$order_type,'style'=>'width:200px;')) ?>
				<?php echo $this->Form->input('order_type',array('label'=>false,'div'=>false,'type'=>'hidden','value'=>$order_type)) ?>
				<?php echo $this->BForm->input('number',array('label'=>'买入数量','type'=>'text','style'=>'width:200px;','default'=>'1')) ?>
				<?php echo $this->BForm->input('price',array('label'=>'买入价格','type'=>'text','style'=>'width:200px;','default'=>'1','after'=>'交易类型之间的价格')); ?>
				<?php echo $this->BForm->input('amount_price',array('label'=>'总价','type'=>'text','style'=>'width:200px;','disabled '=>'disabled ','after'=>'需要从账户冻结的资金')); ?>
				<?php echo $this->BForm->submit('确定'); ?>
				<?php echo $this->Form->end(); ?>
			</div> <!-- /widget-content -->
		</div> <!-- /widget -->	
    </div> <!-- /span6 -->

  </div> <!-- /row -->

</div> <!-- /container -->
<script type="text/javascript">
	$("#OrderNumber,#OrderPrice").change(function(event) {
		var amount = $("#OrderNumber").val()*$("#OrderPrice").val();
		$("#OrderAmountPrice").val(amount.toFixed(6));
	}).change();
</script>