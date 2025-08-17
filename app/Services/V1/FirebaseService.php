<?php

namespace App\Services\V1;

use Log;
use Illuminate\Support\Facades\Storage;
use Google\Client as GoogleClient;

class FirebaseService
{
    public function sendPushFcm($to, $message, $data)
    {
        try {
            $credentialsFilePath = Storage::disk('firebase')->path(env('FIREBASE_SERVICE_ACCOUNT'));
            $client = new GoogleClient();
            $client->setAuthConfig($credentialsFilePath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->refreshTokenWithAssertion();
            $token = $client->getAccessToken();
            $access_token = $token['access_token'];
            $projectId = env('FIREBASE_PROJECT_ID');

            foreach ($data as $key => $value) {
                $data[$key] = (string)$value;
            }

            $results = [];

            foreach($to as $key => $value){
                $strFields = [
                    "message" => [
                        "token" => $value,
                        "notification" => [
                            "title" => $message["title"],
                            "body" =>  $message["body"],
                        ],
                        "android" => [
                            "priority" => "high"
                        ],
                        "data" => $data
                    ]
                ];

                $strHeaders = array(
                    'Content-Type:application/json',
                    "Authorization: Bearer $access_token",
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send");
                curl_setopt($ch, CURLOPT_POST, true );
                curl_setopt($ch, CURLOPT_HTTPHEADER, $strHeaders);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($strFields));
                $srtCurl = curl_exec($ch);

                if($srtCurl === FALSE){
                    $strResult = [
                        "response" => [
                            "status" => "error",
                            "message" => curl_error($ch)
                        ]
                    ];
                } else {
                    $strResult = [
                        "push" => $strFields,
                        "response" => [
                            "status" => "success",
                            "message" => "Push enviado exitosamente"
                        ]
                    ];
                }
                
                $results[] = $strResult;
                curl_close($ch);
            }

            return $results;

        } catch (\Throwable $th) {
            Log::error("Error enviando notificación FCM: " . $th->getMessage());
            return [
                "response" => [
                    "status" => "error",
                    "message" => $th->getMessage()
                ]
            ];
        }
    }
}