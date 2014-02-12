<div class="container">
  <div class="row">
    <div class="span12">
        <div class="widget">
            <div class="widget-header">
                <i class="icon-user"></i>
                <h3>修改GA密码</h3>
            </div> <!-- /widget-header -->
            <div class="widget-content" style="min-height:400px;">
                <?php $is_open = array('0'=>'关闭','1'=>'开启'); ?>
                <p class="alert red">你已经 <b><?php echo $is_open[$this->data['User']['use_ga']]; ?>  </b>google authenticator 密码</p>
                <?php 
                    $options = array('0'=>'关闭','1'=>'开启');
                    unset($options[$user['use_ga']]);
                ?>
                <?php echo $this->BForm->create('User'); ?>
                <?php echo $this->BForm->input('use_ga',array('label'=>'你可以','type'=>'select','options'=>$options,'style'=>'width:200px;')) ?>
                <?php echo $this->BForm->input('ga_password',array('label'=>'GA密码','type'=>'text','style'=>'width:200px;')) ?>
                <?php if (!$user['use_ga']): ?>
                <div class="form-group" id="code-box">
                    <label class="col-sm-2 control-label"></label>
                     <div class="col-sm-10">
                        <img id="qrcode" src="http://chart.apis.google.com/chart?cht=qr&amp;chs=200x200&amp;chld=M|0&amp;cht=qr&amp;chl=otpauth://totp/<?php echo $user['email']; ?>?secret=<?php echo $user['ga_code'] ?>" border="0" width="200" height="200">
                        <p class="red">
                            用户名:<?php echo $user['email']; ?>
                            <br>
                            密匙:<?php echo $user['ga_code']; ?>
                        </p>
                        <div class="alert">
                            <p>用法如下：</p>
                                手机先安装上google authenticator,ios在这，android在这，nokia在这(不能扫描二维码)<br>
                                在手机上打开应用，扫描这个二维码，或者输入本次密钥和标识（如果不能扫描的话）<br>
                                登录的时候填入手机生成的google authenticator密码即可
                        </div>
                    </div>
                </div>
                <?php endif ?>

                <?php echo $this->BForm->submit('确定'); ?>
                <?php echo $this->Form->end(); ?>
            </div> <!-- /widget-content -->
        </div> <!-- /widget --> 
    </div> <!-- /span6 -->

  </div> <!-- /row -->

</div> <!-- /container -->

