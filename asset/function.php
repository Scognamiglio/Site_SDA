<?php

function retour($a){
    $htmlRecur = function ($tab) use (&$htmlRecur){
        foreach ($tab as $k => $v){
            $tab[$k] = (is_array($v)) ? $htmlRecur($v) : htmlentities($v);
        }
        return $tab;
    };

    echo json_encode($htmlRecur($a), JSON_FORCE_OBJECT);
    die();
}

function postDiscord($url,$post){
    global $token;
    $url = "https://discord.com/api/v9/$url";
    $headers = [
        'Content-Type: application/json; charset=utf-8',
        'authorization:Bot '.$token['local'],
        'User-Agent:DiscordBot (https://github.com/discord-php/DiscordPHP, v5.1.1)'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
    $response   = curl_exec($ch);
    return json_decode($response,true);
}

function redirectError(){
    header('Location: '.$_SERVER['PHP_SELF']);
    exit();
}