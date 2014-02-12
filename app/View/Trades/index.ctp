<div class="container">
  <div class="row">
    <div class="span12">
        <div class="widget">
            <div class="widget-header">
                <i class="icon-user"></i>
                <h3>查看我的成交记录</h3>
            </div> <!-- /widget-header -->
            <div class="widget-content">
                <table class="table" >
                    <tr>
                        <th>ID</th>
                        <th>市场</th>
                        <th>交易类型</th>
                        <th>数量</th>
                        <th>成交价格</th>
                        <th>金额</th>
                        <th>手续费</th>
                        <th>创建时间</th>
                    </tr>
                    <?php if ($data): ?>
                    <?php foreach ($data as $key => $one): ?>
                        <tr>
                            <td><?php echo $one['Trade']['id']; ?></td>
                            <td><?php echo $one['Order']['order_type']; ?></td>
                            <td><?php echo $trade_type[$one['Order']['trade_type']]; ?></td>
                            <td><?php echo $one['Trade']['number']; ?></td>
                            <td><?php echo $one['Trade']['price']; ?></td>
                            <td><?php echo $one['Trade']['amount']; ?></td>
                            <td><?php echo $one['Trade']['fee']; ?></td>
                            <td><?php echo date('Y-m-d H:i:s',$one['Trade']['created']); ?></td>
                        </tr>
                    <?php endforeach ?>
                    <?php endif ?>
                </table>
                <?php echo $this->element("pagesb3"); ?>
            </div> <!-- /widget-content -->
        </div> <!-- /widget --> 
    </div> <!-- /span6 -->

  </div> <!-- /row -->

</div> <!-- /container -->