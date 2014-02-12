<div class="block span12">
    <div class="block-heading" data-toggle="collapse" data-target="#tablewidget">交易设置</div>
    <div class="block-body collapse in">
        <table class="table">
            <tr>
                <th>ID</th>
                <th>EMAIL</th>
                <th>用户名</th>
                <th>创建时间</th>
                <th>最后交易</th>
                <th>qq</th>
                <th>电话</th>
                <th>操作</th>
            </tr>
            <?php foreach ($data as $key => $one): ?>
                <tr>
                    <td><?php echo $one['User']['id']; ?></td>
                    <td><?php echo $one['User']['email']; ?></td>
                    <td><?php echo $one['User']['name']; ?></td>
                    <td><?php echo date('Y-m-d H:i:s',$one['User']['created']); ?></td>
                    <td><?php echo $one['User']['last_trade']?date('Y-m-d H:i:s',$one['User']['last_trade']):'-'; ?></td>
                    <td><?php echo $one['User']['qq']; ?></td>
                    <td><?php echo $one['User']['tel']; ?></td>
                    <td>
                        <?php echo $this->Html->link('编辑',array('action'=>'edit','admin'=>true,$one['User']['id'])); ?>
                        <?php echo $this->Html->link('充/扣',array('controller'=>'Accounts','action'=>'alter','admin'=>true,$one['User']['id'])); ?>
                        <?php echo $this->Form->postLink('删除',array('action'=>'delete','admin'=>true,$one['User']['id']),array(),'删除后无法恢复,确定要删除?'); ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
        <?php echo $this->element('pages'); ?>
    </div>
</div>