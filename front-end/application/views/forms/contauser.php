
<?php

$alert_topo = <<<ALERT
<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
ALERT;

?>
<div class="container-fluid">

            <?php echo validation_errors($alert_topo, '</div>'); ?>

            <?php echo form_open('User/newuser', array("class" => "form-horizontal"))?>
            <fieldset>

            <!-- Form Name -->
            <legend>Criar Conta</legend>

            <!-- Text input-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="full_name">Nome Completo</label>  
              <div class="col-md-4">
                  <input id="full_name" name="full_name" value="<?= set_value('full_name') ?>" placeholder="" class="form-control input-md" required="" type="text">

              </div>
            </div>

            <!-- Password input-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="pass1">Senha</label>
              <div class="col-md-4">
                <input id="pass1" name="pass1" value="<?= set_value('pass1') ?>" placeholder="" class="form-control input-md" required="" type="password">

              </div>
            </div>

            <!-- Password input-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="pass2">Confirmar Senha</label>
              <div class="col-md-4">
                <input id="pass2" name="pass2"  value="<?= set_value('pass2') ?>" placeholder="" class="form-control input-md" required="" type="password">

              </div>
            </div>


            <!-- Text input-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="email_user">Email</label>  
              <div class="col-md-4">
              <input id="email_user" name="email_user" value="<?= set_value('email_user') ?>" placeholder="seuemail@dominio.com" class="form-control input-md" required="" type="text">

              </div>
            </div>
            
            <div class="form-group">
              <label class="col-md-4 control-label" for="secreta">Senha Secreta</label>  
              <div class="col-md-4">
              <input id="secreta" name="secreta" value="<?= set_value('secreta') ?>" placeholder="" class="form-control input-md"  type="text">

              </div>
            </div>
           
            <div class="form-group">
              <div class="col-md-4">
                  <input type="submit" name="submit" value="Salvar" class="btn btn-primary" />
              </div>
            </div>

            </fieldset>
            </form>

</div>