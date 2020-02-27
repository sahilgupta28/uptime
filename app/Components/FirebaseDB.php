<?php

namespace App\Components;

use Google\Cloud\Firestore\FirestoreClient;

class FirebaseDB
{
    private $db = [];

    public function __construct()
    {
        $this->db = new FirestoreClient(
            [
                'projectId' => env('FIRESTORE_PROJECT_ID', ''),
                'apiKey' => env('FIRESTORE_WEB_API_KEY', '')
            ]
        );
    }

    public function fs($collection, $data)
    {
        $db_connection = $this->db->collection($collection)->document(sha1(rand() . time()));
        $db_connection->set($data);
        return;
    }

    public function fr($data)
    {
        $query = $this->db->collection($data['collection']);
        if (isset($data['limit']) && $data['limit']) {
            $query = $query->limit($data['limit']);
        }
        if (isset($data['filters']) && $data['filters']) {
            foreach ($data['filters'] as $key => $filter) {
                $key = explode('@', $key);
                if (count($key) == 2) {
                    $query = $query->where($key[0], $key[1], $filter);
                }
            }
        }
        $docs = $query->documents();
        $format_output = [];
        foreach ($docs as $key => $doc) {
            if ($doc->exists()) {
                $format_output[$key] = $doc->data();
                $format_output[$key]['document_id'] = $doc->id();
            }
        }
        return $format_output;
    }

    public function fu($data)
    {
        // dd($data);
        $query = $this->db->collection($data['collection']);
        // if (isset($data['filters']) && $data['filters']) {
        //     foreach ($data['filters'] as $key => $filter) {
        //         $key = explode('@', $key);
        //         if (count($key) == 2) {
        //             $query = $query->where($key[0], $key[1], $filter);
        //         }
        //     }
        // }
        // dd($data['document_id']);
        $query->document($data['document_id'])->update(['_token' => 'A8MKoXOwA1cFALZMXZAPHRyDkWCtJeKSTl7cskdh',
            'created_at' => 'January 24, 2020 13:32:18 - UTC',
            'email' => 'er.sahilgupta1@gmail.com',
            'id' => 202023133218000000,
            'ip_address' => '127.0.0.1',
            'site_name' => 'google',
            'site_url' => 'www.google.com',
            'updated_at' => 'January 24, 2020 13:32:18 - UTC']);
        return;
    }
}
