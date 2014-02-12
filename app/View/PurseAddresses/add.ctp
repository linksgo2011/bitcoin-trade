<div class="block span12">
    <div class="block-heading" data-toggle="collapse" data-target="#tablewidget">添加钱包地址</div>
    <div class="block-body collapse in">
        <p></p>
        <div>
            <?php 
                $this->BForm->formatInput = '<div class="control-group"><label class="control-label">%s</label><div class="controls">%s</div></div>';
             ?>
            <?php echo $this->BForm->create('PurseAddress',array('type'=>'file')); ?>
            <?php echo $this->BForm->input('id',array('label'=>false,'type'=>'hidden','style'=>'width:200px;')); ?>
            <?php echo $this->BForm->input('account_type',array('label'=>'货币类型','type'=>'select','options'=>$account_type_arr,'style'=>'width:200px;')); ?>
            <?php echo $this->BForm->input('add_type',array('legend'=>false,'label'=>'添加方式','default'=>'keys','options'=>array('keys'=>'输入','keys_file'=>'导入'),'type'=>'radio')); ?>
            <div id="keys_div">
                <?php echo $this->BForm->input('keys',array('label'=>'地址码:','type'=>'textarea','style'=>'width:200px;','after'=>'每行一个')); ?>
            </div>
            <div id="keys_file_div">
                <?php echo $this->BForm->input('keys_file',array('label'=>false,'type'=>'file','style'=>'width:200px;','after'=>'每行一个')); ?>
            </div>
            <?php echo $this->BForm->submit('提交'); ?>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<script type="text/javascript"> 
    $("input[type='radio'][name*=add_type]:checked").live('change',function(event) {
        var val = $("input[name*=add_type]:checked").val();
        $("#keys_div,#keys_file_div").hide();
        $("#"+val+"_div").show();
    }).change();;
</script>