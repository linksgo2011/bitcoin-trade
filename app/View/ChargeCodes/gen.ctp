<div class="container">
  <div class="row">
    <div class="span12">
        <div class="widget">
            <div class="widget-header">
                <i class="icon-user"></i>
                <h3>生成充值码</h3>
            </div> <!-- /widget-header -->
            <div class="widget-content">
                <?php if ($user['active'] <= -1): ?>
                    <p class="alert alert-info">用户已经被锁定,无法操作!</p>
                    <?php else: ?>
                    <?php echo $this->BForm->create('ChargeCode'); ?>
                    <?php echo $this->BForm->input('account_type',array('label'=>'账户类型','type'=>'select','options'=>$account_type_arr,'style'=>'width:200px;')) ?>
                    <?php echo $this->BForm->input('amount',array('label'=>'金额','type'=>'text','style'=>'width:200px;')) ?>
                    <?php echo $this->BForm->input('password',array('label'=>'使用密码','type'=>'password','style'=>'width:200px;')) ?>
                    <?php echo $this->BForm->submit('确定'); ?>
                    <?php echo $this->Form->end(); ?>
                <?php endif; ?>
            </div> <!-- /widget-content -->
        </div> <!-- /widget --> 
    </div> <!-- /span6 -->

  </div> <!-- /row -->

</div> <!-- /container -->
