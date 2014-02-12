<style type="text/css">
    select.redirect{
        display:inline-block;
        height:35px;
        line-height:35px;
        border:1px solid #ccc;
    }

</style>
<?php if (isset($this->Paginator)): ?>
<ul class="pagination">
    <?php
        echo $this->MyPages->numbers(array('separator' => '','tag'=>'li','currentClass'=>'active','currentTag'=>'a'));
    ?>
</ul>
<?php endif; ?>

<script type="text/javascript">
    $("select.redirect").change(function(event) {
        var url  = $(this).val();
        window.location.href = url;
    });
</script>