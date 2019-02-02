
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
            
            <?php echo form_open('User/sugestao', array("class" => "form-horizontal", "id"=> "fsugestao"))?>
            <fieldset>

            <!-- Form Name -->
            <legend>Sugestão sobre o sistema</legend>

            <!-- Text input-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="assunto">Assunto</label>  
              <div class="col-md-4">
                  <select id="assunto" name="assunto" class="form-control input-md" required="">
                      <option value="Melhora">Melhora</option>
                      <option value="Problema">Problema</option>
                      <option value="Agradecimento">Agradecimento</option>
                      <option value="Outros">Outros</option>
                  </select>
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-md-4 control-label" for="texto">Texto</label>  
              <div class="col-md-4">
                  <textarea from="fsugestao" cols="30" name="texto" id="texto" required="" value="<?= set_value('texto') ?>"></textarea>
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