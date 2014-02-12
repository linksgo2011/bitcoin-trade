<div class="block span12">
    <div class="block-heading" data-toggle="collapse" data-target="#tablewidget">处理提现申请</div>
    <div class="block-body collapse in">
        <div>
            <h5>记录详情</h5>
            <table class="table">
                <tr>
                    <th>ID</th>
                    <td><?php echo $data['Charge']['id'] ?></td>
                    <th>账户类型</th>
                    <td><?php echo $data['Charge']['account_type'] ?></td>
                    <th>创建时间</th>
                    <td><?php echo date('Y-m-d H:i:s',$data['Charge']['created']) ?></td>
                </tr>
                <tr>
                    <th>用户名</th>
                    <td><?php echo $data['User']['name'] ?></td>
                    <th>邮箱</th>
                    <td><?php echo $data['User']['email'] ?></td>
                    <th>地址</th>
                    <td><?php echo $data['Charge']['address'] ?></td>
                </tr>
                <tr>
                    <th>金额</th>
                    <td><?php echo $data['Charge']['amount'] ?></td>
                </tr>
            </table>
        </div>
        <?php 
            $this->BForm->formatInput = '<div class="control-group"><label class="control-label">%s</label><div class="controls">%s</div></div>';
         ?>
        <?php echo $this->BForm->create('Charge'); ?>
        <?php echo $this->BForm->input('active',array('label'=>'处理方式','options'=>array('1'=>'确认完成','-1'=>'作废'))); ?>
        <?php echo $this->BForm->input('error_mark',array('label'=>'备注:','type'=>'textarea')); ?>
        <?php echo $this->BForm->submit('确认'); ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>