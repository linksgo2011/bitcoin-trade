<div class="block span12">
    <div class="block-heading" data-toggle="collapse" data-target="#tablewidget">充值码管理</div>
    <div class="block-body collapse in">
        <p></p>
        <div class="inline-form row-fluid">
            <?php $this->BForm->formatInput = "%s %s"; ?>
            <?php echo $this->Form->Create('ChargeCode',array('type'=>'get','class'=>'form-inline')); ?>
            <?php echo $this->BForm->input("active",array('label'=>'状态','options'=>$active_arr,'empty'=>'选择状态','required'=>false,'style'=>'width:100px;')); ?>
            <?php echo $this->BForm->input("account_type",array('label'=>'账户类型:','options'=>$account_type_arr,'empty'=>'账户类型:')); ?>
            <?php echo $this->BForm->input("code",array('label'=>'充值码')); ?>
            <?php echo $this->BForm->submit("搜索",array('class'=>'btn')); ?>
            <?php echo $this->Form->end(); ?>
        </div>
        <table class="table">
            <tr>
                <th>ID</th>
                <th>类型</th>
                <th>数量</th>
                <th>状态</th>
                <th>充值码</th>
                <th>密码</th>
                <th>生成者</th>
                <th>使用者</th>
                <th>生成时间</th>
                <th>使用时间</th>
                <!-- <th>操作</th> -->
            </tr>
            <?php foreach ((array)$data as $key => $one): ?>
                <tr>
                    <td><?php echo $one['ChargeCode']['id']; ?></td>
                    <td><?php echo $one['ChargeCode']['account_type']; ?></td>
                    <td><?php echo $one['ChargeCode']['amount']; ?></td>
                    <td><?php echo $active_arr[$one['ChargeCode']['active']] ?></td>
                    <td><?php echo $one['ChargeCode']['code']; ?></td>
                    <td><?php echo $one['ChargeCode']['password']; ?></td>
                    <td><?php echo $one['User']['email']; ?></td>
                    <td><?php echo $one['Used']['email']?$one['Used']['email']:'未使用'; ?></td>
                    <td><?php echo date('Y-m-d H:i:s',$one['ChargeCode']['created']); ?></td>
                    <td><?php echo $one['ChargeCode']['finished']?date('Y-m-d H:i:s',$one['ChargeCode']['finished']):'未使用'; ?></td>
<!--                     <td>
                        <?php if ($one['ChargeCode']['active'] == 0): ?>
                            <?php echo $this->Form->postLink('删除',array('action'=>'delete',$one['ChargeCode']['id']),array(),'是否删除?') ?>
                        <?php endif ?>
                    </td> -->
                </tr>
            <?php endforeach ?>
        </table>
        <?php echo $this->element('pages'); ?>
    </div>
</div>