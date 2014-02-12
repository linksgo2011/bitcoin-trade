<div class="block span12">
    <div class="block-heading" data-toggle="collapse" data-target="#tablewidget">钱包地址管理</div>
    <div class="block-body collapse in">
        <p></p>
        <p>
            <a href="/PurseAddresses/add/" class="btn btn-warning pull-right">添加钱包地址</a>
        </p>
        <div class="inline-form row-fluid">
            <?php $this->BForm->formatInput = "%s %s"; ?>
            <?php echo $this->Form->Create('PurseAddress',array('type'=>'get','class'=>'form-inline')); ?>
            <?php echo $this->BForm->input("account_type",array('label'=>'货币类型','type'=>'select','options'=>$account_type_arr,'empty'=>'选择货币类型','required'=>false,'style'=>'width:100px;')); ?>
            <?php echo $this->BForm->input("active",array('label'=>'状态','options'=>$active_arr,'empty'=>'选择地址状态','required'=>false,'style'=>'width:100px;')); ?>
            <?php echo $this->BForm->input("key",array('label'=>'地址码','required'=>false)); ?>
            <?php echo $this->BForm->submit("搜索",array('class'=>'btn')); ?>
            <?php echo $this->Form->end(); ?>
        </div>
        <table class="table">
            <tr>
                <th>ID</th>
                <th>状态</th>
                <th>货币类型</th>
                <th>地址码</th>
                <th>分配的用户</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
            <?php foreach ($data as $key => $one): ?>
                <tr>
                    <td><?php echo $one['PurseAddress']['id']; ?></td>
                    <td><?php echo $active_arr[$one['PurseAddress']['active']]; ?></td>
                    <td><?php echo $account_type_arr[$one['PurseAddress']['account_type']]; ?></td>
                    <td><?php echo $one['PurseAddress']['key']; ?></td>
                    <td><?php echo $one['User']['email']?$one['User']['email']:'-'; ?></td>
                    <td><?php echo date('Y-m-d H:i:s',$one['PurseAddress']['created']); ?></td>
                    <td>
                        <?php echo $this->Form->postLink("删除",array('action'=>'delete',$one['PurseAddress']['id'])); ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
        <?php echo $this->element('pages'); ?>
    </div>
</div>