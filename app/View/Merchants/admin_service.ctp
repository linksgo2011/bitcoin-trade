<div class="block span12">
    <div class="block-heading" data-toggle="collapse" data-target="#tablewidget">服务设置</div>
    <div class="block-body collapse in">
        <p>
            <div class="alert">请填写你的网站服务账号:
            </div>
        </p>
        <?php 
            $this->BForm->formatInput = '<div class="control-group"><label class="control-label">%s</label><div class="controls">%s</div></div>';
         ?>
        <?php echo $this->BForm->create('Merchant'); ?>
        <?php echo $this->BForm->input('id',array('label'=>false,'type'=>'hidden','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->input('service_tel',array('label'=>'服务电话:','type'=>'text','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->input('service_qq',array('label'=>'服务QQ:','type'=>'text','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->input('service_email',array('label'=>'服务邮箱:','type'=>'text','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->submit('提交'); ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>