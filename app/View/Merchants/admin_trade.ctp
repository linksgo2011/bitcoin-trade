<div class="block span12">
    <div class="block-heading" data-toggle="collapse" data-target="#tablewidget">交易设置</div>
    <div class="block-body collapse in">
        <p>
            <div class="alert">请填写你的网站默认手续费设置:
            </div>
        </p>
        <?php 
            $this->BForm->formatInput = '<div class="control-group"><label class="control-label">%s</label><div class="controls">%s</div></div>';
         ?>
        <?php echo $this->BForm->create('Merchant'); ?>
        <?php echo $this->BForm->input('id',array('label'=>false,'type'=>'hidden','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->input('ars_btc_rate',array('label'=>'ARS_BTC手续比例:','type'=>'text','style'=>'width:200px;','after'=>'%')); ?>
        <?php echo $this->BForm->input('ars_ltc_rate',array('label'=>'ARS_LTC手续比例:','type'=>'text','style'=>'width:200px;','after'=>'%')); ?>
        
        <?php echo $this->BForm->submit('提交'); ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>