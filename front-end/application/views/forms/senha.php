
<?php

$alert_topo_f = <<<ALERT
<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
ALERT;

$alert_topo_s = <<<ALERT
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
ALERT;

?>
<div class="container-fluid">

            <?php echo validation_errors($alert_topo_f, '</div>'); ?>

            <?php runtimeErrors($runerros, $alert_topo_f, '</div>'); ?>
    
            <?php 
            
                if(isset($muser["msg"]))
                {
                    if($muser["type"] == 'S')
                    {
                        echo $alert_topo_s . $muser["msg"] . '</div>';
                    } elseif($muser["type"] == 'F')
                    {
                        echo $alert_topo_f . $muser["msg"] . '</div>';
                    }
                }
                
            
            ?>
            
            <?php echo form_open('User/passrecover', array("class" => "form-horizontal"))?>
            <fieldset>

            <!-- Form Name -->
            <legend>Recuperar Senha</legend>

            <!-- Text input-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="email_user">Email</label>  
              <div class="col-md-4">
              <input id="email_user" name="email_user" value="<?= set_value('email_user') ?>" placeholder="seuemail@dominio.com" class="form-control input-md" required="" type="text">

              </div>
            </div>
           
            <div class="form-group">
              <div class="col-md-4">
                  <input type="submit" name="submit" value="Enviar" class="btn btn-primary" />
              </div>
            </div>

            </fieldset>
            </form>

</div>