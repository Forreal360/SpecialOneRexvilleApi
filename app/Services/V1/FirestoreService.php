<?php

namespace App\Services\V1;

use Log;
use Illuminate\Support\Facades\Storage;
use Google\Client as GoogleClient;

class FirestoreService
{
    private $baseUrl;
    private $projectId;
    
    public function __construct()
    {
        $this->projectId = env('FIREBASE_PROJECT_ID');
        $this->baseUrl = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents";
    }
    
    private function getFirebaseAccessToken()
    {
        $credentialsFilePath = Storage::disk('firebase')->path(env('FIREBASE_SERVICE_ACCOUNT'));
        $client = new GoogleClient();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/datastore');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();
        
        if (!isset($token['access_token'])) {
            throw new \Exception("No se pudo obtener el token de acceso de Firebase");
        }
        
        return $token['access_token'];
    }
    
    public function updateFirestoreDocument(string $collection, string $documentId, array $fields, bool $merge = true)
    {
        try {
            $url = "{$this->baseUrl}/{$collection}/{$documentId}";

            if ($merge) {
                $maskParams = [];
                foreach (array_keys($fields) as $field) {
                    $maskParams[] = "updateMask.fieldPaths={$field}";
                }
                if (!empty($maskParams)) {
                    $url .= '?' . implode('&', $maskParams);
                }
            }

            $firestoreData = [
                "fields" => $this->convertToFirestoreFields($fields)
            ];

            $method = $merge ? 'PATCH' : 'PUT';
            return $this->executeFirestoreRequest($url, $method, $firestoreData, "actualizar");
            
        } catch (\Throwable $th) {
            return $this->handleException($th, "actualizar");
        }
    }

    public function createFirestoreDocument(string $collection, array $fields, ?string $documentId = null)
    {
        try {
            $url = "{$this->baseUrl}/{$collection}";
            if ($documentId) {
                $url .= "?documentId={$documentId}";
            }

            $firestoreData = [
                "fields" => $this->convertToFirestoreFields($fields)
            ];

            $result = $this->executeFirestoreRequest($url, 'POST', $firestoreData, "crear");
            
            if ($result['status'] === 'success' && isset($result['data']['name'])) {
                $documentPath = $result['data']['name'];
                $pathParts = explode('/', $documentPath);
                $result['document_id'] = end($pathParts);
            }
            
            return $result;
            
        } catch (\Throwable $th) {
            return $this->handleException($th, "crear");
        }
    }

    public function deleteFirestoreDocument(string $collection, string $documentId)
    {
        try {
            $url = "{$this->baseUrl}/{$collection}/{$documentId}";
            return $this->executeFirestoreRequest($url, 'DELETE', null, "eliminar");
            
        } catch (\Throwable $th) {
            return $this->handleException($th, "eliminar");
        }
    }
    
    public function getFirestoreDocument(string $collection, string $documentId)
    {
        try {
            $url = "{$this->baseUrl}/{$collection}/{$documentId}";
            $result = $this->executeFirestoreRequest($url, 'GET', null, "obtener");
            
            if ($result['status'] === 'success' && isset($result['data']['fields'])) {
                $result['data_php'] = $this->convertFromFirestoreFields($result['data']['fields']);
            }
            
            return $result;
            
        } catch (\Throwable $th) {
            return $this->handleException($th, "obtener");
        }
    }

    private function executeFirestoreRequest(string $url, string $method, ?array $data, string $operation)
    {
        $access_token = $this->getFirebaseAccessToken();
        
        $headers = [
            'Content-Type: application/json',
            "Authorization: Bearer {$access_token}",
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        if ($data !== null && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            $jsonData = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($response === false) {
            $result = [
                "status" => "error",
                "message" => curl_error($ch),
                "http_code" => $httpCode
            ];
        } else {
            $responseData = $response ? json_decode($response, true) : [];
            $result = [
                "status" => ($httpCode >= 200 && $httpCode < 300) ? "success" : "error",
                "message" => ($httpCode >= 200 && $httpCode < 300) ? 
                    "Documento {$operation}do correctamente" : 
                    "Error al {$operation} documento",
                "data" => $responseData,
                "http_code" => $httpCode
            ];
        }
        
        curl_close($ch);
        return $result;
    }
    
    private function handleException(\Throwable $th, string $operation)
    {
        Log::error("Error al {$operation} documento en Firestore: " . $th->getMessage());
        
        return [
            "status" => "error",
            "message" => $th->getMessage(),
            "trace" => $th->getTraceAsString()
        ];
    }

    private function convertToFirestoreFields(array $data)
    {
        $result = [];
        
        foreach ($data as $key => $value) {
            $result[$key] = $this->getFirestoreValue($value);
        }
        
        return $result;
    }

    private function getFirestoreValue($value)
    {
        if (is_null($value)) {
            return ["nullValue" => null];
        } else if (is_bool($value)) {
            return ["booleanValue" => $value];
        } else if (is_int($value)) {
            return ["integerValue" => (string)$value];
        } else if (is_float($value)) {
            return ["doubleValue" => $value];
        } else if (is_string($value)) {
            return ["stringValue" => $value];
        } else if ($value instanceof \DateTime) {
            return ["timestampValue" => $value->format(\DateTime::RFC3339)];
        } else if (is_array($value)) {
            if (array_keys($value) !== range(0, count($value) - 1)) {
                $mapValues = [];
                foreach ($value as $k => $v) {
                    $mapValues[$k] = $this->getFirestoreValue($v);
                }
                return ["mapValue" => ["fields" => $mapValues]];
            } else {
                $arrayValues = [];
                foreach ($value as $item) {
                    $arrayValues[] = $this->getFirestoreValue($item);
                }
                return ["arrayValue" => ["values" => $arrayValues]];
            }
        }
        
        return ["stringValue" => (string)$value];
    }
    
    private function convertFromFirestoreFields(array $fields)
    {
        $result = [];
        
        foreach ($fields as $key => $value) {
            $result[$key] = $this->getPhpValue($value);
        }
        
        return $result;
    }
    
    private function getPhpValue(array $firestoreValue)
    {
        $type = key($firestoreValue);
        $value = $firestoreValue[$type];
        
        switch ($type) {
            case 'nullValue':
                return null;
            case 'booleanValue':
                return (bool)$value;
            case 'integerValue':
                return (int)$value;
            case 'doubleValue':
                return (float)$value;
            case 'stringValue':
                return $value;
            case 'timestampValue':
                return new \DateTime($value);
            case 'mapValue':
                return $this->convertFromFirestoreFields($value['fields'] ?? []);
            case 'arrayValue':
                $result = [];
                foreach ($value['values'] ?? [] as $item) {
                    $result[] = $this->getPhpValue($item);
                }
                return $result;
            default:
                return $value;
        }
    }
}