<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <style type="text/css">
        .site_close{
            width:400px;
            height:30px;
            line-height:30px;
            margin:0 auto;
            border:1px dashed #f60;
            padding:30px;
            margin-top:200px;
        }
    </style>
    </head>
    <body>
        <div class="site_close">
            对不起网站已经关闭! 请联系管理员: <?php echo $merchant['Merchant']['service_email']; ?>
        </div>
    </body>
</html>