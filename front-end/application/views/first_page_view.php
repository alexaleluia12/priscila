
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12"><h3><?= $subtitle ?></h3></div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <!--listagem dos sinais-->
            <table border="1">
                <thead>
                    <tr>
                        <th>Comprar</th>
                        <th>Vender</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <a href="<?= site_url("User/clearbuy") ?>" title="limpa todos sinais de venda">x limpar</a>
                        </td>
                        <td>
                            <a href="<?= site_url("User/clearsell") ?>" title="limpa todos sinais de compra">x limpar</a>
                        </td>    
                    </tr>
                        
                    <?php foreach ($sinais as $valor): ?>
                    <tr>
                        <td>
                            <?php if(isset($valor["buy"])): ?>
                            <a href="<?= site_url("Stock/drawnew/").$valor["buy"]["id"] ?>"
                               <?php if(in_array($valor["buy"]["id"], $carteira_ids)){echo "class='destaque-sinal'";} ?>
                            >
                                <?= $valor["buy"]["cod"] ?>
                            </a>
                            <!--icone da forca do sinal-->
                            <?= $valor["buy"]["ico"] ?>
                            <a href="<?= site_url("User/clearone/").$valor["buy"]["id"] ?>" title="remove sinal">X</a>
                            <a href="<?= site_url("User/fadd/").$valor["buy"]["id"] ?>" title="acompanha sinal">A</a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if(isset($valor["sell"])): ?>
                            <a href="<?= site_url("Stock/drawnew/").$valor["sell"]["id"] ?>"
                               <?php if(in_array($valor["sell"]["id"], $carteira_ids)){echo "class='destaque-sinal'";} ?>
                            >
                                <?= $valor["sell"]["cod"] ?>
                            </a>
                            <!--icone da forca do sinal-->
                            <?= $valor["buy"]["ico"] ?>
                            <a href="<?= site_url("User/clearone/").$valor["sell"]["id"] ?>" title="remove sinal">X</a>
                            <a href="<?= site_url("User/fadd/").$valor["sell"]["id"] ?>" title="acompanha sinal">A</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-2">
            <!--listagem dos sinais-->
            <table border="1">
                <thead>
                    <tr>
                        <th>Acompanhando</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <a href="<?= site_url("User/fremovelall") ?>">x limpar</a>
                        </td>                        
                    </tr>
                        
                    <?php foreach ($follow as $valor): ?>
                    <tr>
                        <td>
                            
                            <a href="<?= site_url("Stock/drawnew/").$valor["owner"] ?>"><?= $acoes[$valor["owner"]] ?></a>
                            <a href="<?= site_url("User/fremove/").$valor["owner"] ?>" title="deixa de acompanhar sinal">X</a>
                            
                        </td>
                       
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-2">
            <form>
               <label for="codigoAcao">Código</label>  
              
               <input id="codigoAcao" name="codigo" type="text" style="width: 6em;">
               <input type="submit" id="btnAcao" name="submit" value="Buscar" class="btn btn-primary" />
            </form>
            <div id="localList"></div>
            
        </div>
        <div class="col-sm-5">
            <p>Carteira 
                <a class="btn btn-primary" href="<?= base_url() . 'Carteira/operacao?acao=C' ?>">Comprar</a>
                <a class="btn btn-primary" href="<?= base_url() . 'Carteira/operacao?acao=V' ?>">Vender</a>
            </p>
            <table border="1">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Quantidade</th>
                        <th>Preço Médio</th>
                        <th>Lucro</th>
                        <th>%</th>
                        <th>% Hoje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carteira as $valor): ?>
                    <tr>
                        <td>
                            <a href="<?= base_url() . 'Carteira/operacao?acao=C&code=' . $valor["owner"] ?>">C</a>
                            <a href="<?= base_url() . 'Carteira/operacao?acao=V&code=' . $valor["owner"] ?>">V</a>
                            <a href="<?= site_url("Stock/drawnew/").$valor["owner"] ?>"><?= $acoes[$valor["owner"]] ?></a>
                            
                        </td>
                        <td>
                            <?= $valor["amount"] ?>
                        </td>
                        <td>
                            <?= $valor["price_media"] ?>
                        </td>
                        <td>
                            <?= $valor["lucro"] ?>
                        </td>
                        <td class="<?= $valor['plucroclass'] ?>">
                            <?= $valor["plucro"] ?>
                        </td>
                        <td class="<?= $valor['phjclass'] ?>">
                            <?= $valor["phj"] ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td>
                            Total
                        </td>
                        <td>
                            
                        </td>
                        <td>
                            <?= $total["sum_passado"] ?>
                        </td>
                        <td>
                            <?= $total["sum_hoje"] ?>
                        </td>
                        <td>
                            <?= $total["sump_passado"] ?>
                        </td>
                        <td>
                            <?= $total["sump_hoje"] ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
