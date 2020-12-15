<?php

require_once 'funcoes/conversoes.php';
require_once 'funcoes/exibicoes.php';
require_once 'funcoes/manipulacao_pilotos.php';

$pilotos = arquivoParaArray('corrida.log');
$pilotos = agruparInfoPilotos($pilotos);
$pilotos = ordenarPorOrdemChegada($pilotos);
$vencedor = descobrirVencedor($pilotos);
exibirResultados($vencedor, $pilotos);

?>