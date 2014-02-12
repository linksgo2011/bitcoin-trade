<div class="container">
  <div class="row">
    <div class="span12">
        <div class="widget">
            <div class="widget-header">
                <i class="icon-user"></i>
                <h3>使用充值码</h3>
            </div> <!-- /widget-header -->
            <div class="widget-content">
                <div class="row-fluid">
                        <div class="span6">
                            <?php echo $this->BForm->create('ChargeCode'); ?>
                            <?php echo $this->BForm->input('code',array('label'=>'输入充值码','type'=>'text','style'=>'width:200px;')) ?>
                            <?php echo $this->BForm->input('password',array('label'=>'使用密码','type'=>'password','style'=>'width:200px;')) ?>
                            <?php echo $this->BForm->submit('确定'); ?>
                            <?php echo $this->Form->end(); ?>
                        </div>
                        <div class="span6">
                            <div id="code_info">
                                
                            </div>
                        </div>
                </div>

            </div> <!-- /widget-content -->
        </div> <!-- /widget --> 
    </div> <!-- /span6 -->
  </div> <!-- /row -->

</div> <!-- /container -->
<script type="text/javascript">
    $("#ChargeCodeCode").change(function(event) {
        var val = $(this).val();
        var uri = "/ChargeCodes/view/"+val;
        $("#code_info").html("查询中...");
        $("#code_info").load(uri);
    }).change();
</script>
