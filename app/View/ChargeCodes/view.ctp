<?php if ($data): ?>
    <table class="table">
        <tr>
            <th>类型</th>
            <th>数量</th>
            <th>是否有密码</th>
            <th>创建时间</th>
        </tr>
        <tr>
            <td><?php echo strtolower($data['ChargeCode']['account_type']); ?></td>
            <td>
                <?php echo $data['ChargeCode']['amount']; ?>
            </td>
            <td class="red"><?php echo $data['ChargeCode']['password']?'是':'否'; ?></td>
            <td>
                <?php echo date('Y-m-d H:i:s',$data['ChargeCode']['created']); ?>
            </td>
        </tr>
    </table>
    <?php else: ?>
    没有找到该充值码
<?php endif ?>