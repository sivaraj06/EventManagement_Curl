<?php
/*
*Function : Curl call
*Return type : array
*/
function curlHit($endPoint,$postData)
{
    $curl = curl_init();
    curl_setopt($curl,CURLOPT_URL,$endPoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);

    $response = curl_exec($curl);
    curl_close($curl);
    $res_rows = json_decode($response,1);

    return $res_rows;
}
function curlHitresponse($endPoint,$postData)
{
    $curl = curl_init();
    curl_setopt($curl,CURLOPT_URL,$endPoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);

    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}

?>