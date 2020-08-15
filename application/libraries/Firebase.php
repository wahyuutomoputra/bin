<?php

class Firebase {
    
    public function send($data)
    {
        $url = "https://fcm.googleapis.com/fcm/send";
        $serverKey = 'AAAAxK7PBNk:APA91bFRQbQbwmg5d6hdmfYdvnS17esfCDOQTjIvw3nAE1vDWYKEhcP2VL1M_RquYaiBVYK1lEc2RjGJTUhj-pVT5Knv30RxHMUa2dY3uMctmNVN1HysS3ZQWeGVZXvu1HYsIxLOegrx';
     
        $notification = array('title' =>$data['title'] , 'text' => $data['body'], 'sound' => 'default', 'badge' => '1');

        $arrayToSend = array('to' => $data['token'], 'notification' => $notification,'priority'=>'high');
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='. $serverKey;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}

?>