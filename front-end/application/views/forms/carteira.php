
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
    
            <?php runtimeErrors($runerros, $alert_topo, '</div>')?>

            <?php echo form_open('Carteira/operacao', array("class" => "form-horizontal"))?>
            <fieldset>

            <!-- Form Name -->
            <legend>Realizar operação</legend>

            <!-- Text input-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="valor_codigo">Código</label>  
              <div class="col-md-4">
                  <select id="valor_codigo" name="valor_codigo" class="form-control input-md" required="">
                    <?php foreach ($lst_codigos as $valor): ?>
                      <option value="<?= $valor['id'] ?>"
                              <?php if(set_value('valor_codigo')== $valor['id']){ echo "selected='selected'";}  ?>
                      >
                            <?= $valor['cod'] ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
              </div>
            </div>

            <!-- Password input-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="operacao">Operação</label>
              <div class="col-md-4">
                <label class="radio-inline">
                    <input id="operacao" name="operacao" value="C" <?php if ( $acao == "C" ) echo 'checked'; ?>  class="form-control input-md" required="" type="radio">
                    Compra
                </label>
                <label class="radio-inline">
                    <input id="operacao" name="operacao" value="V" <?php if ( $acao == "V" ) echo 'checked'; ?> class="form-control input-md" required="" type="radio">
                    Venda
                </label>
              </div>
            </div>

            <!-- Password input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="preco">Preço</label>
              <div class="col-md-4">
                <input id="preco" name="preco"  value="<?= set_value('preco') ?>" placeholder="" class="form-control input-md" type="text">
              </div>
            </div>
            
            <!-- Password input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="qnt">Quantidade</label>
              <div class="col-md-4">
                <input id="qnt" name="qnt"  value="<?= set_value('qnt') ?>" placeholder="" class="form-control input-md" required="" type="text">
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