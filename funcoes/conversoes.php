<?php

// Converte horas em formato string para milisegundos
function horasStrParaMilisegundos($horaStr) {
    $divisor = explode(':', $horaStr);
    $milisegundos = 0;

    switch (sizeof($divisor)):
        case 3:
            $milisegundos += $divisor[0] * 60 * 60 * 1000;
            $milisegundos += $divisor[1] * 60 * 1000;
            $divisorSegundos = explode('.', $divisor[2]);

            if (sizeof($divisorSegundos) === 2):
                $milisegundos += $divisorSegundos[0] * 1000;
                $milisegundos += $divisorSegundos[1];
            endif;
            break;

        case 2:
            $milisegundos += $divisor[0] * 60 * 1000;
            $divisorSegundos = explode('.', $divisor[1]);

            if (sizeof($divisorSegundos) === 2):
                $milisegundos += $divisorSegundos[0] * 1000;
                $milisegundos += $divisorSegundos[1];
            endif;
            break;
    endswitch;

    return $milisegundos;
}

// Converte milisegundos para horas em formato string de acordo com o formato passado.
// Formatos possíveis: h:m:s.s ou m:s.s
function milisegundosParaHorasStr($milisegundos, $formato) {
    $divisor = explode(':', $formato);
    $horasStr = '';

    switch (sizeof($divisor)):
        case 3:
            $horas = floor($milisegundos / 1000 / 60 / 60);
            $minutos = floor($milisegundos / 1000 / 60 - $horas * 60);
            $divisorSegundos = explode('.', $divisor[2]);

            $horasStr .= str_pad(floor($horas), 2, '0', STR_PAD_LEFT) . ':' . str_pad(floor($minutos), 2, '0', STR_PAD_LEFT);

            if (sizeof($divisorSegundos) === 2):
                $segundos = floor($milisegundos / 1000 - ($minutos * 60) - ($horas * 3600));
                $milisegundos -= ($segundos * 1000 + $minutos * 60000 + $horas * 3600000);

                $horasStr .= ':' . str_pad(floor($segundos), 2, '0', STR_PAD_LEFT) . '.' . str_pad(floor($milisegundos), 3, '0', STR_PAD_LEFT);
            endif;
            break;

        case 2:
            $minutos = floor($milisegundos / 1000 / 60);
            $divisorSegundos = explode('.', $divisor[1]);

            $horasStr .= str_pad(floor($minutos), 2, '0', STR_PAD_LEFT);

            if (sizeof($divisorSegundos) === 2):
                $segundos = floor($milisegundos / 1000 - $minutos * 60);
                $milisegundos -= ($segundos * 1000 + $minutos * 60000);

                $horasStr .= ':' . str_pad(floor($segundos), 2, '0', STR_PAD_LEFT) . '.' . str_pad(floor($milisegundos), 3, '0', STR_PAD_LEFT);
            endif;
            break;
    endswitch;

    return $horasStr;
}

// Transforma as informações do arquivo em um array de linhas e colunas
function arquivoParaArray(
    $nomeArquivo,
    $separadorColunas = ' '
) {
    $contadorLinhas = 0;
    $arquivo = fopen($nomeArquivo, "r");
    $array = array();

    while (!feof($arquivo)):
        $linha = fgets($arquivo);
        $colunas = explode($separadorColunas, $linha);

        foreach ($colunas as $indice => $coluna):
            $coluna = str_replace('–', '', trim($coluna));

            if ($coluna !== ''):
                $array[$contadorLinhas][] = $coluna;
            endif;
        endforeach;

        $contadorLinhas++;
    endwhile;

    return $array;
}

?>