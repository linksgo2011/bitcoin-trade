<?php if ($merchant['Merchant']['html_index']): ?>
    <?php echo $merchant['Merchant']['html_index']; ?>
    <?php else: ?>

<div class="masthead">
    <h3 class="text-muted">比特币交易系统</h3>
</div>
<!-- Jumbotron -->
<div class="jumbotron">
    <h1>快速开始!</h1>
    <p class="lead">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet.</p>
    <p><a class="btn btn-lg btn-success" href="/Users/home" role="button">现在开始</a></p>
</div>
<!-- Example row of columns -->
<div class="row">
    <div class="col-lg-4">
        <h2>Safari bug warning!</h2>
        <p class="text-danger">Safari exhibits a bug in which resizing your browser horizontally causes rendering errors in the justified nav that are cleared upon refreshing.</p>
        <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
        <p><a class="btn btn-primary" href="#" role="button">View details &raquo;</a></p>
    </div>
    <div class="col-lg-4">
        <h2>Heading</h2>
        <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
        <p><a class="btn btn-primary" href="#" role="button">View details &raquo;</a></p>
    </div>
    <div class="col-lg-4">
        <h2>Heading</h2>
        <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa.</p>
        <p><a class="btn btn-primary" href="#" role="button">View details &raquo;</a></p>
    </div>
</div>
<!-- Site footer -->
<div class="footer">
    <p>&copy; Company 2013</p>
</div>
<?php endif ?>
