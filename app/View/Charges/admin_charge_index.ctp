<div class="block span12">
    <div class="block-heading" data-toggle="collapse" data-target="#tablewidget">充值记录</div>
    <div class="block-body collapse in">
        <p></p>
        <div class="inline-form row-fluid">
            <?php $this->BForm->formatInput = "%s %s"; ?>
            <?php echo $this->Form->Create('Charge',array('type'=>'get','class'=>'form-inline')); ?>
            <?php echo $this->BForm->input("active",array('label'=>'状态','options'=>$active_arr,'empty'=>'选择状态','required'=>false,'class'=>'span2')); ?>
            <?php echo $this->BForm->input("account_type",array('label'=>'币种','options'=>$account_type_arr,'empty'=>'请选择币种')); ?>
            <?php echo $this->BForm->input("email",array('label'=>'邮箱')); ?>
            <?php echo $this->BForm->submit("搜索",array('class'=>'btn')); ?>
            <?php echo $this->Form->end(); ?>
        </div>
        <table class="table">
            <tr>
                <th>ID</th>
                <th>用户</th>
                <th>账户类型</th>
                <th>数量</th>
                <th>备注</th>
                <th>操作时间</th>
                <th>充值地址</th>
                <th>操作</th>
            </tr>
            <?php foreach ($data as $key => $one): ?>
                <tr>
                    <td><?php echo $one['Charge']['id']; ?></td>
                    <td><?php echo $one['User']['email']; ?></td>
                    <td><?php echo $account_type_arr[$one['Charge']['account_type']]; ?></td>
                    <td><?php echo $one['Charge']['amount']; ?></td>
                    <td><?php echo $one['Charge']['mark']; ?></td>
                    <td><?php echo date('Y-m-d H:i:s',$one['Charge']['created']); ?></td>
                    <td>
                        <?php echo $one['Charge']['address']; ?>
                    </td>
                    <td>
                        <?php if ($one['Charge']['active'] == 0): ?>
                            <?php echo $this->Html->link('处理',array('action'=>'charge_deal','admin'=>true,$one['Charge']['id'])); ?>
                            <?php else: ?>
                            -                        
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
        <?php echo $this->element('pages'); ?>
    </div>
</div>