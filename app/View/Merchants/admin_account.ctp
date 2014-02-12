<div class="block span12">
    <div class="block-heading" data-toggle="collapse" data-target="#tablewidget">账户设置</div>
    <div class="block-body collapse in">
        <p>
            <div class="alert">请填写你的网站汇款信息</div>
        </p>
        <?php 
            $this->BForm->formatInput = '<div class="control-group"><label class="control-label">%s</label><div class="controls">%s</div></div>';
         ?>
        <?php echo $this->BForm->create('Merchant'); ?>
        <?php echo $this->BForm->input('id',array('label'=>false,'type'=>'hidden','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->input('charge_info',array('label'=>'汇款信息:','type'=>'textarea','style'=>'width:200px;')); ?>
        <?php echo $this->BForm->submit('提交'); ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
<?php echo $this->Html->script('/js/kindeditor/kindeditor-min.js'); ?>
<script type="text/javascript">
    KindEditor.ready(function(K) {
        editor = K.create('#MerchantChargeInfo', {
            resizeType : 0,
            allowPreviewEmoticons : false,
            uploadJson : '/MediaAdResources/ajaxAdd/MediaAdResourceMediaAdResTypeId:1',
            allowImageUpload : false,
            width: '80%',
            height: '400px',
            items : [
                'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'strikethrough', 'removeformat', 
                '|', 
                'justifyleft', 'justifycenter', 'justifyright',
                'justifyfull', 'insertorderedlist', 'insertunorderedlist', 
                'lineheight',
                '|',
                'link', 'unlink',
                '|', 
                'image', 'table', 'media','map','source'
            ]
        });
    });
</script>