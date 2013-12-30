# HAL 10K - Trading bot

**HAL10K** é um Bitcoin trading/helper bot escrito em PHP (open source). Ele foi desenvolvido para ser usado sobre a API do MTGox, mas poderá ser facilmente adaptado a outras exchanges. Suas decisões são baseadas em parametros customizáveis e técnicas básicas para análise de mercado. Ele também funciona em modo semi-automático, onde em cada ação de prejuízo(stop-loss) solicita via Twitter a confirmação remota de seu operador. Além do modo "Live Trading" ele também executa simulações "Backtesting" utilizando "Fake Balance" e dados históricos do [http://bitcoincharts.com/charts/mtgoxUSD](http://bitcoincharts.com/charts/mtgoxUSD). Todas suas ações(compra/venda) e alertas(volumes altos detectados) são notificados via Twitter. Seu log de resultados é acompanhado através de painel de controle HTTP onde o bot exibe seu próprio gráfico de desempenho.

More details at: [github.com/intrd/bitcoin/hal10k](https://github.com/intrd/bitcoin/hal10k/)
