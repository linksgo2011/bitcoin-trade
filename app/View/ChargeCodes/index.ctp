<div class="container">
  <div class="row">
    <div class="col-md-12">
        <div class="widget">
            <div class="widget-header">
                <i class="icon-list"></i>
                <h3>充值码</h3>
            </div> <!-- /widget-header -->
            <div class="widget-content" style="min-height:400px;">
                <p>
                   <a href="/ChargeCodes/use_code" class="btn btn-primary">使用充值码</a> 
                   <a href="/ChargeCodes/gen" class="btn btn-primary">生成充值码</a> 
                </p>
                <p></p>
                <h4>我的充值码</h4>
                <table class="table">
                    <tr>
                        <th>ID</th>
                        <th>类型</th>
                        <th>数量</th>
                        <th>状态</th>
                        <th>充值码</th>
                        <th>密码</th>
                        <th>生成时间</th>
                        <th>使用时间</th>
                        <th>操作</th>
                    </tr>
                    <?php foreach ((array)$data as $key => $one): ?>
                        <tr>
                            <td><?php echo $one['ChargeCode']['id']; ?></td>
                            <td><?php echo $one['ChargeCode']['account_type']; ?></td>
                            <td><?php echo $one['ChargeCode']['amount']; ?></td>
                            <td><?php echo $active_arr[$one['ChargeCode']['active']] ?></td>
                            <td><?php echo $one['ChargeCode']['code']; ?></td>
                            <td><?php echo $one['ChargeCode']['password']; ?></td>
                            <td><?php echo date('Y-m-d H:i:s',$one['ChargeCode']['created']); ?></td>
                            <td><?php echo $one['ChargeCode']['finished']?date('Y-m-d H:i:s',$one['ChargeCode']['finished']):'未使用'; ?></td>
                            <td>
                                <?php if ($one['ChargeCode']['active'] == 0): ?>
                                    <?php echo $this->Form->postLink('删除',array('action'=>'delete',$one['ChargeCode']['id']),array(),'是否删除?') ?>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </table>
                <?php echo $this->element('pages'); ?>
            </div>
        </div>
    </div>
  </div> <!-- /row -->
</div> <!-- /container -->
