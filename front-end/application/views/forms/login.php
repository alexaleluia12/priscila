

<?php
$alert_topo = <<<ALERT
<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
ALERT;

?>

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-login">                
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <?php echo validation_errors($alert_topo, '</div>'); ?>
                            
                            <?php runtimeErrors($runerros, $alert_topo, '</div>'); ?>

                            <?php echo form_open('User/login', array("role" => "form", "style"=> "display: block;", "id"=>"login-form"))?>
                                <div class="form-group">
                                    <input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="seuemail@dominio" value="<?= set_value('username') ?>">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-sm-offset-3 text-center">
                                            <input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Log In">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="text-center">
                                                <a href="<?= base_url() . 'User/passrecover' ?>" tabindex="5" class="forgot-password">Esqueceu a senha</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>		
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>