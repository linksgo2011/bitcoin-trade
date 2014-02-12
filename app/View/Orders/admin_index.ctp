<div class="block span12">
    <div class="block-heading" data-toggle="collapse" data-target="#tablewidget">查看<?php echo $arr[$_GET['trade_type']]; ?></div>
    <div class="block-body collapse in">
        <p></p>
        <div class="inline-form row-fluid">
            <?php $this->BForm->formatInput = "%s %s"; ?>
            <?php echo $this->Form->Create('Order',array('type'=>'get','class'=>'form-inline')); ?>
            <?php echo $this->BForm->input("trade_type",array('label'=>false,'type'=>'hidden')); ?>
            <?php echo $this->BForm->input("active",array('label'=>'状态','options'=>$active_arr,'empty'=>'选择挂单状态','required'=>false,'style'=>'width:100px;')); ?>
            <?php echo $this->BForm->input("order_type_arr",array('label'=>'交易类型','options'=>$order_type_arr,'empty'=>'交易类型')); ?>
            <?php echo $this->BForm->input("email",array('label'=>'邮箱')); ?>
            <?php echo $this->BForm->submit("搜索",array('class'=>'btn')); ?>
            <?php echo $this->Form->end(); ?>
        </div>
        <table class="table">
            <tr>
                <th>ID</th>
                <th>状态</th>
                <th>用户名</th>
                <th>交易类型</th>
                <th>当前数量</th>
                <th>价格</th>
                <th>当前总价</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
            <?php foreach ($data as $key => $one): ?>
                <tr>
                    <td><?php echo $one['Order']['id']; ?></td>
                    <td><?php echo $active_arr[$one['Order']['active']]; ?></td>
                    <td><?php echo $one['User']['name'] ?></td>
                    <td><?php echo $one['Order']['order_type']; ?></td>
                    <td><?php echo $one['Order']['number']; ?></td>
                    <td><?php echo $one['Order']['price']; ?></td>
                    <td><?php echo $one['Order']['amount_price']; ?></td>
                    <td><?php echo date("Y-m-d H:i:s",$one['Order']['created']); ?></td>
                    <td>
                        <!-- Todo 如果成交,每个单子会产生订单明细 -->
                        <a href="javascript:void(0);" ectype="dialog" dialog_id="view_trades" dialog_title="成交明细" dialog_width="700" uri="/ajax/Trades/index/<?php echo $one['Order']['id'] ?>" >成交明细</a>
                    </td>   
                </tr>
            <?php endforeach ?>
        </table>
        <?php echo $this->element('pages'); ?>
    </div>
</div>