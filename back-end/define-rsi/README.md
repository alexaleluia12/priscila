projeto rsi


Objeto Serie
     (seriet)array de ponto{preco, data}
    
    metodos
        retoran o indice data foi encontrado
        PontoData(data int) int, ok
        
        
    * em diversos momento é realizado operacoes com uma sub serie de seriet
      propor um metodo geral que recebe uma funcao e trabalha com 
      a serie internamente para eveitar consumos execivo de memoria
      
      
      operacaoG(indiceI int, indiceF, fn, (nao sei talvez tenha mais))
      
      o que fazo com a seriet
        aplico algoritmo genetico
        calculo serie do rsi
        gero sinais de compra/venda
        processo esses sinais


#TODO
v 1 . inciar serie temporal pelo banco de dados
v 2 . PontoData com busca binaria
v 3 . operacaoG para calculo do ris(limiteCompra, limiteVenda)
v 4 . ag 
    cromossomo(season, limitOverBound, limitOverSold)
v 5 . generate the buy/sell signals from RSI serie and time serie
v 6 . process the buy/sell signals
v 7 . simulations, find the beast pair training/test, (profit, hit hat) 
v 8 . graphic stock, rsi, signals, two moving average (codeigniter application)


resumo das simulações
{
    total(soma de todas as simulacoes) se refere a porcentagem de lucro. DP = desvio padrão
    *** melhor resultado
}
(sem consultar tendencia, 6 meses treino)
total, media = -29.2, dp = 47.6
--
(com consulta de tendencia, 6 meses treino)
total, media = -21.9, dp = 51.1
--
(sem consultar tendencia, tempo treino 4 meses)
total, media = -9.5, dp = 37.5
--
(sem consultar tendencia, treino 4 meses, com stop 6%) ***
total, media = -1.4, dp = 47.9
--
(com consulta tendencia, treino 4 meses)
total, media = -29.6, dp = 33.3
--
(com consulta tendencia, treino 4 meses, com stop 6%)
total, media = -25.5193783519999, dp = 44.2



 == nova simulacao ==

(sem consultar tendencia, 6 meses treino)
28 media =  -1.3797396000000084 , dp =  6.9328352028466576
27 media =  -11.091615099999988 , dp =  1.2554324633981877
80 media =  21.15252819999999 , dp =  9.652622892328901
112 media =  -40.791496061999986 , dp =  6.381106174421371
114 media =  14.640291600000022 , dp =  10.576350181756
15 media =  -11.710238599999968 , dp =  12.784817417647503
total, media = -29.2, dp = 47.6

(com consulta de tendencia, 6 meses treino)
28 media =  3.011156300000006 , dp =  7.062697776451893
27 media =  -4.915097999999998 , dp =  6.019785556269057
80 media =  16.007638899999996 , dp =  18.52873940927751
112 media =  -32.07296586999997 , dp =  7.446337589118116
114 media =  20.921006700000042 , dp =  8.652221899200057
15 media =  -24.880971499999966 , dp =  3.439628830773258
total, media = -21.9, dp = 51.1

(sem consultar tendencia, tempo treino 4 meses)
28 media =  -1.769251999999986 , dp =  6.218538071884296
27 media =  -8.97262409999998 , dp =  4.76200207749332
80 media =  26.885266600000005 , dp =  2.728622070732503
112 media =  -44.502910994999965 , dp =  6.525814071149164
114 media =  19.73589020000003 , dp =  9.191076926581632
15 media =  -0.8414077999999684 , dp =  8.128291146122727
total, media = -9.5, dp = 37.5

(sem consultar tendencia, treino 4 meses, com stop 6%) ***
28 media =  1.7736148000000291 , dp =  14.353032353116488
27 media =  -13.066616899999985 , dp =  5.092398611505679
80 media =  31.573831900000027 , dp =  2.6906621103579433
112 media =  -29.988376750999958 , dp =  7.357696894641599
114 media =  9.180075600000032 , dp =  18.205164051710078
15 media =  -7.595212499999976 , dp =  0.2401215057186709
total, media = -1.4, dp = 47.9


(com consulta tendencia, treino 4 meses)
28 media =  -5.479116000000002 , dp =  2.870850268712457
27 media =  6.024415300000011 , dp =  1.867360521247768
80 media =  2.4671414000000076 , dp =  4.934282800000015
112 media =  -30.918520863999994 , dp =  9.64480074699654
114 media =  2.302788900000018 , dp =  3.862787676668539
15 media =  -4.047335499999976 , dp =  10.1491972550105
total, media = -29.6, dp = 33.3


(com consulta tendencia, treino 4 meses, com stop 6%)
28 media =  -9.226999999999995 , dp =  5.774548592147673
27 media =  -7.409221399999995 , dp =  0.12039129194148049
80 media =  6.653715200000008 , dp =  19.667578742604498
112 media =  -12.243300651999963 , dp =  4.998872228356006
114 media =  2.604223800000033 , dp =  10.70812501425246
15 media =  -5.897795299999988 , dp =  2.952855937286532
total, media = -25.5193783519999, dp = 44.2



*** horivel, mas eu vou fazer o melhor novamente pois a minha simulacao nao esta muito correta
(sem consultar tendencia, treino 4 meses, com stop 6%, analise de stop)
28 media =  -2.3538185999999732 , dp =  5.412837206053917
27 media =  -20.87403999999997 , dp =  6.2416100081089025
80 media =  17.496164500000027 , dp =  16.010353607601143
112 media =  -37.66534208199994 , dp =  7.203338160350108
114 media =  19.200985100000043 , dp =  15.671797327132264
15 media =  -3.8379369999999726 , dp =  9.26420785434202
total, media = -28.03398808199978, dp = 59.8

+++++++

(sem consultar tendencia, treino 4 meses, com stop 6%)
28 media =  0.7379928000000253 , dp =  5.701459331150171
27 media =  -15.48446719999996 , dp =  3.958891788230574
80 media =  32.95570580000001 , dp =  28.301594571764216
112 media =  -19.636854402999933 , dp =  16.056524009895845
114 media =  11.154380400000047 , dp =  17.66250466051353
15 media =  2.2834694000000098 , dp =  8.284661182508614
total, media = 12.010226797000202, dp = 79.96563554406295

(acima novamente)
28 media =  10.880263800000034 , dp =  11.697426584315476
27 media =  -18.41264469999995 , dp =  2.7226227486091266
80 media =  38.99568750000003 , dp =  24.767222123724395
112 media =  -44.55610277899996 , dp =  8.57555880066435
114 media =  24.64752280000004 , dp =  20.279103111012837
15 media =  -0.7628239999999862 , dp =  8.162900299721343
total, media = 10.8

28 media =  8.419839100000035 , dp =  5.58684714451222
27 media =  -13.829757699999965 , dp =  9.354402783988723
80 media =  20.196217800000017 , dp =  15.79352734314435
112 media =  -42.75852595299994 , dp =  4.884801677401487
114 media =  27.00690780000006 , dp =  14.374680913853593
15 media =  -9.823658299999988 , dp =  9.197627586472981
total, media = -10.8

28 media =  -2.5684908999999756 , dp =  4.205009332197402
27 media =  -16.226056499999963 , dp =  7.273114626677076
80 media =  27.809192400000008 , dp =  13.99510241195349
112 media =  -29.933781841999963 , dp =  7.758684963237381
114 media =  19.378230700000046 , dp =  7.536584492878572
15 media =  -21.223399099999973 , dp =  3.135827583530833
total, media = -22.0

(sem consultar tendencia, treino 4 meses, com stop 6%, sem ag)
28 -0.22
27 -7.32
80 36.81
112 -26.65
114 29.89
15 -27.20
total = 5.3


(com acima + by, referencia preco compra)****************
28 media =  -2.4131186999999885 , dp =  4.100815999435295
27 media =  -17.11863819999997 , dp =  7.891428964460621
80 media =  64.66248360000002 , dp =  32.46221883038318
112 media =  -36.04470434999998 , dp =  10.616539789803326
114 media =  39.52659600000001 , dp =  7.603840871341707
15 media =  -10.848263899999994 , dp =  9.063286178331317
total, media = 37.76

(com acima + by, referencia preco compra)
28 media =  -3.892123799999979 , dp =  4.478036606990568
27 media =  -14.904854499999967 , dp =  5.89828951283309
80 media =  41.290269400000014 , dp =  35.56471236011101
112 media =  -47.70866404199995 , dp =  3.9501828827307173
114 media =  38.960941200000015 , dp =  6.168694068775659
15 media =  -14.114355099999983 , dp =  12.829987059114035
total, media = -0.368786841999853

(com acima + by, referencia preco alto)
28 media =  0.27200260000001786 , dp =  4.996649079173654
27 media =  -24.900932099999956 , dp =  5.5603336629448545
80 media =  46.76699840000002 , dp =  31.872775226281075
112 media =  -47.68562947199994 , dp =  7.159280437326573
114 media =  12.581046600000025 , dp =  19.63931697307563
15 media =  -8.024909499999989 , dp =  9.272700958371084
total, media = -20.9





coisa que pose ver eh a existencia de suporte sobre um ponto de compra ajuda

-- baixa -- 
8 meses treino
for 112 media =  -39.01877017999998 , dp =  4.26544626852459

10 meses treino
112 media =  -37.61378123199998 , dp =  5.8127420526243
80 media =  4.0632233000000015 , dp =  8.126446600000003 (treino grande crescente nao funciona bem)

3 meses tive lucro
lucro 122 9.388825454999987



media2 := serie.SMA(v.Sindex, 5)
					tend1 := media2.Trend()
					if tend1 == serietools.THIGHT || tend1 == serietools.TSIDE {
					

// if analisar 2 tendencias
serieLonga := serie.SMA(v.Sindex, timeTrendLong)
					serieCurta := serie.SMA(v.Sindex, timeTrendShort)
					trend1 := serieCurta.Trend()
					trend2 := serieLonga.Trend()
					if trend1 == trend2 && (trend1 == serietools.TLOW || trend1 == serietools.THIGHT) {
					
############################
versao estatica

1. -33.08, stop
2. -23.07, stp + tendencia
3. 50.66, stop + tendencia + 6/4 treino/teste
4. 113.23, stop + 6/4 treino/teste
4.totas.  1129.21
ultima. 3144.94 - mat7.txt (pequeno de talhe errado nas simulacoes)
2821.00 === mehor ==== mat8.txt


{
1800.79
1899.35
}

