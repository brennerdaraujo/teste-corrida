<?php

// Retorna a msg com o tempo, em milisegundos, que os nao vencedores chegaram após o vencedor.
// Se o piloto não tem a msm qtde de voltas do vencedor, é retornada msg de não término da corrida.
function msgTempoAposVencedor($vencedor, $piloto) {
    if ($piloto['posicao'] !== 1):
        $tempoAposVencedorMs = calculaTempoAposVencedor($vencedor, $piloto);

        if ($tempoAposVencedorMs !== false):
            return $tempoAposVencedorMs . ' milisegundos';
        else:
            return 'Não terminou a corrida';
        endif;
    else:
        return 'Vencedor';
    endif;
}

// Exibe todos os resultados obtidos do piloto e da corrida.
function exibirResultados($vencedor, $pilotos) {
    $melhorTempoCorrida = 0;
    $melhorVoltaCorrida = 0;
    $melhorPilotoCorrida = '';

    echo '<table style="border: 1px solid silver; padding: 10px; width: 100%;">';
    echo '<thead>';
    echo '<tr>';
    echo '<th style="text-align: center">Posição</th>';
    echo '<th style="text-align: center">Código</th>';
    echo '<th style="text-align: center">Nome</th>';
    echo '<th style="text-align: center">Qtde voltas</th>';
    echo '<th style="text-align: center">Tempo total</th>';
    echo '<th style="text-align: center">Melhor volta</th>';
    echo '<th style="text-align: center">Velocidade média</th>';
    echo '<th style="text-align: center">Tempo após vencedor</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($pilotos as $piloto):
        $voltas = $piloto['voltas'];
        $melhorTempoVolta = 0;
        $velocidadeTotal = 0;
        $tempoTotal = 0;
        $qtdeVoltas = sizeof($voltas);

        foreach ($voltas as $volta):
            $velocidadeTotal += (float) str_replace(',', '.', $volta['velocMedia']);
            $voltaMs = horasStrParaMilisegundos($volta['tempo']);
            if (
                $melhorTempoVolta === 0
                || $melhorTempoVolta > $voltaMs
            ):
                $melhorTempoVolta = $voltaMs;
                $piloto['melhorVolta'] = $volta['numero'];
            endif;
            $tempoTotal += $voltaMs;
        endforeach;

        if (
            $melhorTempoCorrida === 0
            || $melhorTempoCorrida > $melhorTempoVolta
        ):
            $melhorTempoCorrida = $melhorTempoVolta;
            $melhorVoltaCorrida = isset($piloto['melhorVolta']) ? $piloto['melhorVolta'] : 0;
            $melhorPilotoCorrida = $piloto['nomePiloto'];
        endif;

        echo '<tr>';
        echo '<td style="text-align: center">' . $piloto['posicao'] . '</td>';
        echo '<td style="text-align: center">' . $piloto['codPiloto'] . '</td>';
        echo '<td style="text-align: center">' . $piloto['nomePiloto'] . '</td>';
        echo '<td style="text-align: center">' . $qtdeVoltas . '</td>';
        echo '<td style="text-align: center">' . milisegundosParaHorasStr($tempoTotal, 'm:s.s') . '</td>';
        echo '<td style="text-align: center">' . $piloto['melhorVolta'] . 'ª</td>';
        echo '<td style="text-align: center">' . str_replace('.', ',', round(($velocidadeTotal / $qtdeVoltas), 3)) . '</td>';
        echo '<td style="text-align: center">' . msgTempoAposVencedor($vencedor, $piloto) . '</td>';
        echo '</tr>';
    endforeach;

    echo '</tbody>';
    echo '</table>';
    echo '<p style="text-align: center;">Melhor volta da corrida: ' . $melhorVoltaCorrida . 'ª - ' . $melhorPilotoCorrida . '</p>';
}

?>