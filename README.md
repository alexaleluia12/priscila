#RSIP (Relative Strength Indicator Project) - Priscila


Eu gosto muito do mercado de ações por isso iniciei esse projeto, continuação do meu TCC.
Minha intensão era usar esse projeto comercialmente porém perdi o interesse no seu proposito.
Modelo autônomo (robô) que fala o momento e o que deve comprar/vender.

Desisti do projeto porque o Yahoo cortou a API publica de ações, o preço de uma API considero caro.
Também porque no longo prazo a compra constante de ações tem um bom resultado (Livro: O investidor inteligente, Benjamin Graham)

Possui:

```txt
php - página web para cliente gerenciar sua cartera e ver os sinais novos.
go - atualizar preço
go - listar preço
go - define regra de operação (rsi)
```

Esse projeto já é a versão 3.

1. Python (troquei porque estava muito lento)
2. C++ (troquei porque tive problemas em fazer likagen statica do mysql)
3. Go (compilado, statico, rápido, roda em qualquer lugar, likagen estática, **otima escolha**)


## Descrição detalhada
```txt

nome Priscila:
    vem das siglas RSIP. P de Project, RSI é abreviação de Relative Strength Indicator.


Objetivos:
    Auxiliar na compra e venda de ações através da indicação de sinais e gráficos.
    Gerenciar sua carteira de ações.
        - não consideramos taxa de custodia ou corretagem, apenas o preço pago pela ação e a quantidade.
        - apresentamos a variação diária de todas as ações em carteira
        - não acompanhamos os lucros com a venda de ações, apenas gerenciamos os ganhos que estão em carteira
          assim o nosso sistema pode ser usando em conjunto com outros HomeBroker.
    Os sinais que geramos para compra/venda de ações são para médio e longo preço.


como surgiu:
    Vem da continuação de Trabalho de Conclusão de Curso de um aluno de Ciência da Computação.
    Do estudo e técnica de muitos artigos e diversas simulações.


como os sinais são lançados:
    Cada ação tem uma configuração de RSI diferente obtida através técnicas computacionais
    que visão o maior lucro. Com essa configurações usamos o princípio de
    sobre vendido e sobre comprado do RSI para lançar os sinais.


o melhor investimento (Compra):
    seguros: sinal do RSI + preço está em tendência de alta clara, com os valores do preço acima
             da média móvel de 50 e 100 dias.
    ariscados: preço está lateral ou em baixa. Tem chance de entrar em tendência de alta e ganhar 
        uma grande valoração em curto espaço de tempo.

o melhor investimento (Venda):
    nos damos o sinal de venda porém acreditamos que segurar é um boa opção.
    A decisão de vender é uma das mais difíceis e deve ser baseado na sua estratégia de Trade.
```

