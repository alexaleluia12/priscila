<?php

class Stock extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("stock_model");
        
    }
    
    public function all()
    {
        $this->load->model('Follow_model');
        $this->load->helper('url_helper');
        $this->load->model('Carteira_model');
        $resp = $this->stock_model->get();
        $user_id = getuserid();
        $sinais = $this->stock_model->buysell($user_id);
        $lst_folow = $this->Follow_model->get($user_id);
        $lst_carteira = $this->Carteira_model->getAll($user_id);
        $map = array();
        foreach ($resp as $elemento)
        {
            $map[$elemento["id"]] = $elemento["cod"];
        }
        $dados["title"] = "Home";
        $dados["subtitle"] = "Todas ações";
        $dados["acoes"] = $map;
        
        $mapType = array("B" => "Comprar", "S" => "Vender");
        
        // http://fontawesome.io/icon/battery-quarter/
        $lstStrengthIcons = array(
            "0.3" => '<i class="fa fa-battery-1" aria-hidden="true"></i>',
            "0.6" => '<i class="fa fa-battery-2" aria-hidden="true"></i>',
            "0.9" => '<i class="fa fa-battery" aria-hidden="true"></i>'
        );
        
        /*
         * 
         * pensar melhor como eu vou mostar essa tabela nao quero q fique dados dispesos
         * nao quero encher a view de coisa
         * 
         * numa unica passagem eu tenho q preenche os dois
         * <tr>
                    <td></td>
                    <td></td>
           </tr>
         * ter o link com codigo[id]
         * <a href="<?= site_url("stock/draw/").$key ?>"><?= $value ?></a>
         * 
         */
        $Tbuy = 'B';
        $listagem = array();
        $listCompra = array();
        $listVenda = array();
        foreach ($sinais as $value)
        {
            $velement = array(
                'id' => $value['owner'], 
                'cod' => $map[$value['owner']],
                'ico' => $lstStrengthIcons[$value['strength']]
            );
            
            if($value['type'] == $Tbuy) 
            {
                $listCompra[] = $velement;
            } else 
            {
                $listVenda[] =   $velement;
            }
        }
        $lenCompra = count($listCompra);
        $lenVenda = count($listVenda);
        $max = $lenCompra >= $lenVenda ? $lenCompra : $lenVenda;
        for($i=0; $i<$max; $i++)
        {
            $listagem[] = array("buy" => $this->getNoBlank($i, $listCompra), 
                                "sell" => $this->getNoBlank($i, $listVenda));
        }
        
        // posso tirar isso pq, eu passo o map do code na view
        
        $sumt_passado = 0.0;
        $sumt_hoje = 0.0;
        $sump_hoje = 0.0;
        $sumtp_passado = 0.0;
        $max = count($lst_carteira);
        $lst_id_carteira = array();
        for($i = 0; $i < $max; $i++)
        {
            // sem atribuicao por refencia os novos valores setados nao sao vistos na view
            $obj = &$lst_carteira[$i];
            $qnt = (int) $obj["amount"];
            $preco = (float) $obj["price_media"];
            $ultimo = (float) $obj["ultimo"];
            $penultimo = (float) $obj["penultimo"];
            $vlucro = ($ultimo - $preco) * $qnt;
            $obj["lucro"] = round($vlucro, 2);
            
            $lst_id_carteira[] = $obj['owner'];
            
            $vplucro = ($ultimo - $preco) / $preco * 100.0;
            $obj["plucro"] = round($vplucro, 2);
            
            $vphjlucro = ($ultimo - $penultimo) / $penultimo * 100.0;
            $obj["phj"] = round($vphjlucro, 2);
            
            $sumt_hoje += $obj["lucro"];
            $sumt_passado += ($qnt * $preco);
            $sump_hoje += $obj["phj"];
            $sumtp_passado += $obj["plucro"];
            
            // transforma os dados para serem apresentados
            $obj["amount"] = number_format($qnt, 0, ',', '.');
            $obj["lucro"] =  number_format($obj["lucro"], 0, ',', '.');
            $obj["plucro"] = $obj["plucro"] > 0 ? '+' . $obj["plucro"] : $obj["plucro"];
            $obj["plucroclass"] = $obj["plucro"] > 0 ? "c-green" : "c-red";
            $obj["phj"] = $obj["phj"] > 0 ? '+' . $obj["phj"] : $obj["phj"];
            $obj["phjclass"] = $obj["phj"] > 0 ? "c-green" : "c-red";
        }
        
        $sumt_hoje = number_format($sumt_hoje, 0, ',', '.');
        $sumt_passado = number_format($sumt_passado, 0, ',', '.');
        
        $total = array(
            "sum_hoje" => $sumt_hoje,
            "sump_hoje" => $sump_hoje,
            "sum_passado" => $sumt_passado,
            "sump_passado" => $sumtp_passado
        );
        
        
        $dados["sinais"] = $listagem;
        $dados["follow"] = $lst_folow;
        $dados["carteira"] = $lst_carteira;
        $dados["carteira_ids"] = $lst_id_carteira;
        $dados["total"] = $total;
        
        $this->load->view("templates/header", $dados);
        $this->load->view("first_page_view", $dados);
        $this->load->view("templates/footer", $dados);
        
    }
    
    private function getNoBlank($index, &$lst)
    {
        if(array_key_exists($index, $lst))
        {
            return $lst[$index];
        } else 
        {
            return NULL;
        }
    }
    
    private function toDeg($rad_value)
    {
       return $rad_value * (180.0/pi());
    }
    
    private function get_tendencia($angulo)
    {
        $saida = "lateral";
        $limit_alta = 0.3;
        $limit_biaxa = -0.5;
        if($angulo > $limit_alta)
        {
            $saida = "alta";
        } elseif($angulo < $limit_biaxa)
        {
            $saida = "baixa";
        }
        
        return $saida;
    }
    
    public function draw($id)
    {
        $this->load->helper('date');
        $this->load->library("MyConstants");
        $this->load->library("SerieUtils");
        // arrumar MyConstants
        $agora = now("America/Sao_Paulo");
        $formatDate = "Y-m-d";
        $dateObj = DateTime::createFromFormat($formatDate, mdate("%Y-%m-%d", $agora));
        
        $endDate = $dateObj->format($formatDate);
        $dateObj->modify("-1 year");
        $startDate = $dateObj->format($formatDate);
        
        $startDate = "2015-10-10";
        $longStart = "2015-02-01";
        $endDate = "2017-08-02";
        $resp = $this->stock_model->get_serie($id, $startDate, $endDate);
        $empresa = $this->stock_model->get($id);
        $confRSI = $this->stock_model->rsiConf($id)[0];
                
        $lstPrice = array();
        $lstPriceShow = array();
        $lstDate = array();
        foreach ($resp->result_array() as $element)
        {
            $tmp = (float) $element["close"];
            $lstPrice[] = $tmp;
            
            $valorDate = $element["date"];
            if($valorDate >= $startDate)
            {
                $lstPriceShow[] = $tmp;
                $lstDate[] = $element["date"];
            }
        }
        $season = (int) $confRSI["season"];
        $pMediaCurta = 60;
        $pMediaLonga = 98;
        
        $lstRSI = $this->serieutils->rsi($lstPrice, $season);
        $lstMMcurta = $this->serieutils->sma($lstPrice, $pMediaCurta);
        $lstMMLonga = $this->serieutils->sma($lstPrice, $pMediaLonga);
        $pontos_analise = 7;
        $pontos_tend = 30;
        
        $soma_dif = 0;
        $len_alvo = count($lstMMcurta);
        for($i=1; $i<=$pontos_tend; $i++)
        {
            
            $v1 = $lstMMcurta[$len_alvo - $i];
            $v2 = $lstMMcurta[$len_alvo - ($i+ 1)];
            
            $soma_dif += ($v1 - $v2);
        }
        $ang_mcurta = $soma_dif;
        $tend_mcurta = $this->get_tendencia($ang_mcurta);
        
        
        $soma_dif = 0;
        $len_alvo = count($lstMMLonga);
        for($i=1; $i<=$pontos_tend; $i++)
        {
            
            $v1 = $lstMMLonga[$len_alvo - $i];
            $v2 = $lstMMLonga[$len_alvo - ($i + 1)];
            
            $soma_dif += ($v1 - $v2);
        }
        
        $ang_mlonga = $soma_dif;
        $tend_mlonga = $this->get_tendencia($ang_mlonga);
        
        
        $tmpLabel1 = (string) $pMediaCurta;
        $tmpLabel2 = (string) $pMediaLonga;
        $labelRsi = "RSI " . $season;
        // add rsi
        $out1 = array();
        $out2[] = ["Data", $labelRsi];
        $i = 0;
        $a = 0;
        $b = 0;
        $max = count($lstPrice);
        for($j=0; $j<$max; $j++)
        {
            if($j<$season)
            {
                $vrsi = NULL;
            } else 
            {
                $vrsi = $lstRSI[$i];
                $i++;
            }
            
            if($j<$pMediaCurta)
            {
                $vmmCurta = NULL;
            } else
            {
                $vmmCurta = round($lstMMcurta[$a], 2);
                $a++;
            }
            
            if($j<$pMediaLonga)
            {
                $vmmLonga = NULL;
            } else 
            {
                $vmmLonga = round($lstMMLonga[$b], 2);
                $b++;
            }
            
            $tmpPriceX = round($lstPrice[$j], 2);
            $out1[] = [$lstDate[$j], $tmpPriceX, $vmmCurta, $vmmLonga];
            $out2[] = [$lstDate[$j], $vrsi];
        }

        $dados["data1"] = json_encode($out1);
        $dados["data2"] = json_encode($out2);
        $dados["width"] = '90%' ;
        $dados["height"] = '300px';
        $dados["nome1"] = $tmpLabel1;
        $dados["nome2"] = $tmpLabel2;
        $dados["ytitle"] = "Preço";
        $dados["xtitle"] = "Data";
        $dados["title1"] = "rsi";
        $dados["title2"] = "---";
        $dados["title"] = $empresa[0]["cod"];
        
        // -- mostra a tendencia
        $dados['ang_mcurta'] = $ang_mcurta;
        $dados['tend_mcurta'] = $tend_mcurta;
        
        $dados['ang_mlonga'] = $ang_mlonga;
        $dados['tend_mlonga'] = $tend_mlonga;
        
        $this->load->view("templates/header", $dados);
        $this->load->view("grafic_view", $dados);
        $this->load->view("templates/footer", $dados);
    }
    
    
    public function drawnew($id)
    {
        $this->load->helper('date');
        $this->load->library("MyConstants");
        $this->load->library("SerieUtils");
        // arrumar MyConstants
        $agora = now("America/Sao_Paulo");
        $formatDate = "Y-m-d";
        $dateObj = DateTime::createFromFormat($formatDate, mdate("%Y-%m-%d", $agora));
        
        $endDate = $dateObj->format($formatDate);
        $dateObj->modify("-1 year");
        $startDate = $dateObj->format($formatDate);
        
        // quando tiver mais dados pegar menos 2 anos partir de $dateObj
        $longStart = "2015-01-02";
        $resp = $this->stock_model->get_serie($id, $longStart, $endDate);
        $empresa = $this->stock_model->get($id);
        $confRSI = $this->stock_model->rsiConf($id)[0];
                
        $lstPrice = array();
        
        // extrai o preco
        foreach ($resp->result_array() as $element)
        {
            $tmp = (float) $element["close"];
            $lstPrice[] = $tmp;
        }
        
        $season = (int) $confRSI["season"];
        $lbuy = (int) $confRSI["buy"];
        $lsell = (int) $confRSI["sell"];
        // media movel de 200 dias tambem eh muito utilizado porem nao tenho adas para isso
        $pMediaCurta = 50;
        $pMediaLonga = 100;
        $lstRSI = $this->serieutils->rsi($lstPrice, $season);
        $lstMMcurta = $this->serieutils->sma($lstPrice, $pMediaCurta);
        $lstMMlonga = $this->serieutils->sma($lstPrice, $pMediaLonga);
        
        
        /*
         * deixar esse grafico ainda melhor se eu conseguir desenhar uma linha horizontal
         * sobre o preco, para identificar possiveis suporte/resistencia
         * 
         * quando tiver mais dados permitir que a pessoa dusque dados mais antigos
         * permitir que ela defina e salve para si o valor de mm que desejar
         * 
         * mudar o formato da data
         * https://stackoverflow.com/questions/32148482/how-to-format-date-and-time-in-dygraphs-legend-according-to-user-locale
         */
        $newData = '"Data,Preço,'. "MM($pMediaCurta),MM($pMediaLonga)" . '\n';
        $showRSI = '"Data,RSI(' . $season . '),Compra,Venda\n';
        $indexRSI = -$season;
        $indexMMLonga = -$pMediaLonga;
        $indexMMCurta = -$pMediaCurta;
        $indexCount = 0;
        foreach ($resp->result_array() as $element)
        {
            $valorDate = $element["date"];
            $tmp = (float) $element["close"];
            
            // busca mais dados porem so mostra diferenca de um ano
            if($valorDate >= $startDate)
            {
                $tmp = round($tmp, 2);
                $vmmLonga = round($lstMMlonga[$indexMMLonga], 2);
                $vmmCurta = round($lstMMcurta[$indexMMCurta], 2);

                $valorDate = str_replace("-", "", $valorDate);
                $newData .= $valorDate . ',' . $tmp . ',' . $vmmCurta . ',' . $vmmLonga . '\n';
                
                $valueRSI = $lstRSI[$indexRSI];
                $valueRSI = round($valueRSI, 2);
                $showRSI .= $valorDate . ',' . $valueRSI . ',' . $lbuy . ',' . $lsell . '\n';
            }
            $indexRSI++;
            $indexCount++;
            $indexMMCurta++;
            $indexMMLonga++;
        }
        $newData .= '"';
        $showRSI .= '"';
        

        $dados["csv"] = $newData;
        $dados["rsi"] = $showRSI;
        $dados["width"] = '85%' ;
        $dados["height"] = '280px';
        $dados["heightRSI"] = '200px';
        $dados["title"] = $empresa[0]["cod"];
        
        
        $this->load->view("templates/header", $dados);
        $this->load->view("dgra", $dados);
        $this->load->view("templates/footer", $dados);
    }
    
    public function inicio()
    {
      $data["title"] = "dygraphs";
      $this->load->view("templates/header", $data);
      $this->load->view("g2", $data);
      $this->load->view("templates/footer", $data);
    }
    
    public function codelist($code)
    {
        $this->load->library('session');
        if(!$this->session->userdata('user_id'))
        {
            show_404();
        } else 
        {
            $dados = $this->stock_model->getByCode($code);
            $outAux = array();
            if(count($dados) > 0)
            {
                $outAux["ok"] = true;
                $outAux["dados"] = $dados;
                
            } else
            {
                $outAux["ok"] = false;
                $outAux["dados"] = array();
            }
            
            $this->output->set_content_type('application/json')
                ->set_output(json_encode($outAux));
        }
    }
}
