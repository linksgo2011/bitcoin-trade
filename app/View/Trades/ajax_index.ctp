<div style="width:100%;">
    <table class="table" >
        <tr>
            <th>ID</th>
            <th>成交数量</th>
            <th>成交价格</th>
            <th>金额</th>
            <th>手续费</th>
            <th>创建时间</th>
        </tr>
        <?php if ($data): ?>
        <?php foreach ($data as $key => $one): ?>
            <tr>
                <td><?php echo $one['Trade']['id']; ?></td>
                <td><?php echo $one['Trade']['number']; ?></td>
                <td><?php echo $one['Trade']['price']; ?></td>
                <td><?php echo $one['Trade']['amount']; ?></td>
                <td><?php echo $one['Trade']['fee']; ?></td>
                <td><?php echo date('Y-m-d H:i:s',$one['Trade']['created']); ?></td>
            </tr>
        <?php endforeach ?>
        <?php endif ?>
    </table>
    <?php echo $this->element("pages"); ?>
</div>