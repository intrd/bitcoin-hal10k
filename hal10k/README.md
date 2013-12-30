# HAL 10K - Trading bot

**HAL10K** é um Bitcoin trading/helper bot escrito em PHP (open source). Ele foi desenvolvido para ser usado sobre a API do MTGox, mas poderá ser facilmente adaptado a outras exchanges. Suas decisões são baseadas em parametros customizáveis e técnicas básicas para análise de mercado. Ele também funciona em modo semi-automático, onde em cada ação de prejuízo(stop-loss) solicita via Twitter a confirmação remota de seu operador. Além do modo "Live Trading" ele também executa simulações "Backtesting" utilizando "Fake Balance" e dados históricos do [http://bitcoincharts.com/charts/mtgoxUSD](http://bitcoincharts.com/charts/mtgoxUSD). Todas suas ações(compra/venda) e alertas(volumes altos detectados) são notificados via Twitter. Seu log de resultados é acompanhado através de painel de controle HTTP onde o bot exibe seu próprio gráfico de desempenho.

O **HAL10K** foi desenvolvido "from scratch", sem tomar como base nenhum dos agorítmos/técnicas de trading existentes na rede. Este projeto é um exercício de aprendizagem em Trading/Economia para mim, minha experiência se resume em programação e ao Bitcoin. Sim, eu já estou obtendo lucros reais com este Trading bot, e a ideia de abrir o código fonte partiu do princípio de que compartilhando experiências, juntos poderemos melhorar muito o algorítmo e conseguir lucros ainda maiores.

**Contato**: @[intrd](http://twitter.com/intrd) at Twitter or email [contact@dann.com.br](mailto:contact@dann.com.br)   
**Doações**: BTC Wallet: [19kAWVN553KyoU7vx9pYXu8ShVUsPVXzig](https://blockchain.info/address/19kAWVN553KyoU7vx9pYXu8ShVUsPVXzig)   
Estou aberto à dúvidas/sugestões, qualquer colaboração no código será bem vinda.    
O autor e seus contribuintes deste projeto não se responsabilizam por eventuais perdas.     

**Tks to**
* @thaleslaray 
* @rafaelchaguri 
* Wladimir Crippa: [http://nerdices.com.br/](http://nerdices.com.br/) 
* Daniel Fraga: [http://www.youtube.com/user/DanielFragaBR](http://www.youtube.com/user/DanielFragaBR) 
* FB Comunidade Bitcoin Brasil: [http://www.facebook.com/groups/480508125292694](http://www.facebook.com/groups/480508125292694) 
* Reddit: [/r/bitcoin](http://www.reddit.com/r/bitcoin) 

## Exemplo de backtesting

![](http://dann.com.br/chart_sample.png)

`
Período de simulação: 21/12/2013 até 27/12/2013    
**Transactions log**
Iniciando com 1.03161308 BTC @ 700 
$ask [btc0/usd:699.43] @ $678 (lucro) #transaction  
$bid [btc1.07/usd:0] @ $649.43505 (lucro) #transaction  
$ask [btc0/usd:721.03] @ $672.513 (lucro) #transaction  
$bid [btc1.08/usd:0] @ $665.41 (lucro) #transaction     
$ask [btc0/usd:775.37] @ $718.795 (lucro) #transaction  
$bid [btc1.14/usd:0] @ $679.995 (lucro) #transaction    
$ask [btc0/usd:795.73] @ $701 (lucro) #transaction  
$bid [btc1.14/usd:0] @ $701 (nulo) #transaction     
Finalizando com 1.14 BTC @ 701 
`

**Main features & configs**
+Exchanges suportadas   
- MTGox (caso ainda não possua acesso a API do gox, acesse: https://www.mtgox.com/security e crie sua chave (com permissões de leitura/escrita));    
**Log de resultados em Texto**    
**Beeps sonoros ao executar transações (diferenciados para lucros/perdas)**    
**Parâmetros de trading**  
* up_diff - (venda) pontos de lucro em USD acima do preço de compra;    
* up_diff_inv - (venda) stop de prejuízo em USD abaixo do preço de compra;  
* down_diff - (compra) pontos de lucro em USD abaixo do preço de venda;     
* down_diff_inv - (compra) stop de prejuízo em USD acima do preço de venda;     
* percentual - Percentual mínimo de lucro na compra (Use sempre o valor da fee atual aplicada pelo MTGox, defina o lucro real na variável up_diff);    
* secure_ticker - Valor de segurança que impede o bot de fazer vendas abaixo de um valor determinado;   
* interval - Intervalo do loop do bot (em segundos);    
* timeout - Timeout em sergundos p/ conclusão da compra/venda;  
* sudden_mode - Quando ativo, faz a compra no preço de venda e a venda no preço de compra. Usado apenas quando existe a necessidade de processamento imediato da ordem. Desativado após a ordem ser processada; (MUITA ATENÇÃO AO ATIVAR ESTA FUNÇÃO)     
* reverse_prices - O mesmo que o Sudden, porém definitivo. (MUITA ATENÇÃO AO ATIVAR ESTA FUNÇÃO)    
* manualstoploss - Quando ativo, em cada ação de stop-loss solicita a confirmação remota de seu operador; (MUITA ATENÇÃO AO DESATIVAR O MANUAL STOP LOSS)    
* dire - Quantidade de intervalos passados usados na identificação da direção de mercado;   
* dire_limbo - Variação mínima em USD para definir se a direção já saiu ou nao do Limbo(momento em que o bot ainda não conseguiu definir uma direção);     
* vol_limbo - Volume mínimo para considerar uma alteração anormal no volume de um intervalo para outro;     
* fake - Liga/desliga a simulação (Backtesting); (AO DESATIVAR O BACKTESTING VOCÊ IRÁ ATIVAR O LIVE TRADING, MUITA ATENÇÃO)    
* fake_btc_balance - Quantidade inicial de BTC para a simulação;    
* fake_btc_usd_buyedprice - Preço inicial de compra do BTC fake balance;    
**Notificações via Twitter**  
* enable_tweet - Ativa/desativa notificações;   
* twitter_oauth - dados para acesso a API do Twitter, caso não possua, acesse: https://dev.twitter.com/apps e crie sua chave;  
* twitter_users - @usuários do Twitter que deverão ser notificados;     
* Notificações de alto volume via twitter;  
**Interface gráfica** 
* Log de resultados Gráfico (período, last action, asks/bids);  
**HTTP control**  
* Sudden mode (Ativa o sudden mode imediatamente, a password são os dois dígitos do minuto do datetime exibido no cabeçalho acima do gráfico, ex: 01 para o datetime: 2013-12-30 04:01:18, foi feito desta forma para impedir que você execute o sudden duas vezes acidentalmente num refresh da página);  
* Death mode (Desliga o bot temporariamente, a Password é "meuovo123654", pode ser alterada no arquivo: makesudden.php)     
**Instalação e execução**   

O bot deve ser configurado no arquivo "configs.php" e inicializado através do arquivo "hal10k.bat" (edite o "hal10k.bat" p/ corrigir default path: C:\xampp\htdocs\hal10k)   

Este projeto foi desenvolvido no ambiente descrito abaixo, portanto é recomendável rodar na mesma configuração.     
    - XAMPP version 1.7.7 (não incluso no projeto)  
        + Apache 2.2.21 (rodando como serviço)  
        + PHP 5.3.8 (VC9 X86 32bit thread safe) + PEAR  

Libs utilizadas (inclusas no projeto)   
    + pChart 2.1.3 para geração dos gráficos;   
    + tmhOAuth para notificações via Twitter;   

`    
HAL10K Bitcoin trading bot
@copyright (C) http://dann.com.br - @intrd (Danilo Salles) <contact@dann.com.br>

HAL10K is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License 2
as published by the Free Software Foundation.
 
HAL10K is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
`
