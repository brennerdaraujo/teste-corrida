<?php

// Retorna as informações agrupadas de cada volta do piloto
function agruparInfoPilotos($array) {
    $pilotos = array();
    $codsPilotos = array();

    foreach ($array as $indice => $arr):
        $codPiloto = isset($arr[1]) ? $arr[1] : '';
        $voltas = [
            'hora' => isset($arr[0]) ? $arr[0] : '',
            'numero' => isset($arr[3]) ? $arr[3] : 0,
            'tempo' => isset($arr[4]) ? $arr[4] : '',
            'velocMedia' => isset($arr[5]) ? $arr[5] : ''
        ];
        $chaveEncontrada = array_search($codPiloto, $codsPilotos);

        if ($chaveEncontrada !== false):
            $pilotos[$chaveEncontrada]['voltas'][] = $voltas;
        else:
            $pilotos[] = [
                'codPiloto' => $codPiloto,
                'nomePiloto' => isset($arr[2]) ? $arr[2] : '',
                'voltas' => [$voltas]
            ];
            $codsPilotos[] = $codPiloto;
        endif;
    endforeach;

    return $pilotos;
}

// Retorna os pilotos por ordem de chegada se baseando na ultima hora em que ele chegou
function ordenarPorOrdemChegada($pilotos) {
    $grupoNroVoltas = array();
    $ultimasHoras = array();

    foreach ($pilotos as $piloto):
        $nroVoltas = sizeof($piloto['voltas']);
        $grupoNroVoltas[$nroVoltas][] = $piloto;
    endforeach;

    foreach ($grupoNroVoltas as $grupo):
        foreach ($grupo as $piloto):
            $ultimaHora = 0;

            foreach ($piloto['voltas'] as $volta):
                $ultimaHora = $ultimaHora > horasStrParaMilisegundos($volta['hora'])
                    ? $ultimaHora
                    : horasStrParaMilisegundos($volta['hora']);
            endforeach;

            $ultimasHoras[$piloto['codPiloto']] = $ultimaHora;
        endforeach;
    endforeach;

    asort($ultimasHoras);

    $contadorPosicao = 1;
    $pilotosOrdenados = array();

    foreach ($ultimasHoras as $codPiloto => $ultimaHora):
        foreach ($pilotos as $piloto):
            if ($piloto['codPiloto'] === $codPiloto):
                $pilotosOrdenados[] = array_merge($piloto, [
                    'posicao' => $contadorPosicao,
                    'ultimaHora' => $ultimaHora
                ]);
            endif;
        endforeach;

        $contadorPosicao++;
    endforeach;

    return $pilotosOrdenados;
}

// Retorna o vencedor da corrida
function descobrirVencedor($pilotos) {
    foreach ($pilotos as $piloto):
        if ($piloto['posicao'] === 1) return $piloto;
    endforeach;

    return false;
}

// Retorna o tempo, em milisegundos, que os pilotos não vencedores chegaram após o vencedor.
function calculaTempoAposVencedor($vencedor, $piloto) {
    $qtdeVoltasVencedor = sizeof($vencedor['voltas']);
    $ultimaHoraVencedor = $vencedor['ultimaHora'];
    $qtdeVoltasPiloto = sizeof($piloto['voltas']);

    if ($qtdeVoltasVencedor === $qtdeVoltasPiloto):
        return $piloto['ultimaHora'] - $ultimaHoraVencedor;
    else:
        return false;
    endif;
}

?>