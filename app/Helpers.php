<?php


function site(string $param = null): string
{
    if ($param !== null) {
        return SITE[$param];
    }
    return SITE["root"];
}

function assets($path): string
{
    return site() . "/assets/" . $path;
}

function ajax($type, array $param)
{
    return json_encode(["type" => $type, "pm" => $param]);
}

function month(int $m = null)
{
    $d = (int)date('m');
    $month = array();
    $month[1] = "Janeiro";
    $month[2] = "Fevereiro";
    $month[3] = "Março";
    $month[4] = "Abril";
    $month[5] = "Maio";
    $month[6] = "Junho";
    $month[7] = "Julho";
    $month[8] = "Agosto";
    $month[9] = "Setembro";
    $month[10] = "Outubro";
    $month[11] = "Novembro";
    $month[12] = "Dezembro";

    return $m ? $month[$m] : $month[$d];
}