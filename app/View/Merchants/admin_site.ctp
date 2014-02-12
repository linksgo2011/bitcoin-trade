<div class="block span12">
    <div class="block-heading" data-toggle="collapse" data-target="#tablewidget">网站设置</div>
    <div class="block-body collapse in">
        <p>
            <div class="alert">请填写你的网站设置信息</div>
        </p>
        <?php 
            $this->BForm->formatInput = '<div class="control-group"><label class="control-label">%s</label><div class="controls">%s</div></div>';
         ?>
        <?php echo $this->BForm->create('Merchant'); ?>
        <?php echo $this->BForm->input('id',array('label'=>false,'type'=>'hidden','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->input('active',array('label'=>'是否开启','options'=>$active_arr,'default'=>'0','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->input('domain',array('label'=>'域名:','type'=>'text','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->input('site_name',array('label'=>'网站名称:','type'=>'text','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->input('site_title',array('label'=>'网站标题:','type'=>'text','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->input('site_keyword',array('label'=>'网站关键字:','type'=>'text','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->input('site_description',array('label'=>'网站描述:','type'=>'textarea','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->input('site_aboutus',array('label'=>'关于本系统:','type'=>'textarea','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->input('site_pub',array('label'=>'网站公告:','type'=>'textarea','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->submit('提交'); ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>  