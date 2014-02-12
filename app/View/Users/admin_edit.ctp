<div class="block span12">
    <div class="block-heading" data-toggle="collapse" data-target="#tablewidget">网站设置</div>
    <div class="block-body collapse in">
        <p>
            <div class="alert">修改用户信息</div>
        </p>
        <?php 
            $this->BForm->formatInput = '<div class="control-group"><label class="control-label">%s</label><div class="controls">%s</div></div>';
         ?>
        <?php echo $this->BForm->create('User'); ?>
        <?php echo $this->BForm->input('id',array('label'=>false,'type'=>'hidden','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->input('active',array('label'=>'状态:','options'=>$active_arr,'type'=>'select','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->input('use_ga',array('label'=>'使用GA密码','type'=>'select','options'=>array('0'=>'关闭','1'=>'开启'),'style'=>'width:200px;')) ?>
        <?php echo $this->BForm->input('email',array('label'=>'邮箱:','type'=>'text','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->input('name',array('label'=>'用户名:','type'=>'text','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->input('qq',array('label'=>'qq:','type'=>'text','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->input('tel',array('label'=>'电话:','type'=>'text','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->input('password',array('label'=>'密码:','type'=>'password','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->input('pay_password',array('label'=>'支付密码:','type'=>'password','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->input('ars_btc_rate',array('label'=>'ARS_BTC市场手续比例:','type'=>'text','style'=>'width:200px;','after'=>'%')); ?>
        <?php echo $this->BForm->input('ars_ltc_rate',array('label'=>'ARS_LTC市场手续比例:','type'=>'text','style'=>'width:200px;','after'=>'%')); ?>
        <?php echo $this->BForm->submit('提交'); ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>