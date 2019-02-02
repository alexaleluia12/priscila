
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

<div class="container">

    <div class="row">
        <?php $this->load->view('forms/menuform'); ?>
        
        <div class="col-sm-8">
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
            
            <?php echo form_open('User/password', array("class" => "form-horizontal"))?>
            <fieldset>

            <!-- Form Name -->
            <legend>Senha Nova</legend>

           <div class="form-group">
                <label class="col-sm-6 control-label" for="passold" title="senha atual">Senha Antiga</label>  
              <div class="col-sm-6">
              <input id="passold" name="passold" class="form-control input-md" type="password">

              </div>
            </div> 
            
            <!-- Text input-->
            <div class="form-group">
                <label class="col-sm-6 control-label" for="pass1" title="pelo menos 8 digitos">Senha</label>  
              <div class="col-sm-6">
              <input id="pass1" name="pass1" class="form-control input-md" type="password">

              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-6 control-label" for="pass2">Confirmação Senha</label>  
              <div class="col-sm-6">
                  <input id="pass2" name="pass2" class="form-control input-md" type="password">
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
    </div>
</div>