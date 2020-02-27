<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Firestore\FirestoreClient;
use App\Components\FirebaseDB;

class InfoController extends Controller
{
    private $_konnectFirestoreClient = [];
    private $firebase_db;

    public function __construct()
    {
        $this->_konnectFirestoreClient = new FirestoreClient(
            [
                'projectId' => env('FIRESTORE_PROJECT_ID', ''),
                'apiKey' => env('FIRESTORE_WEB_API_KEY', '')
            ]
        );
        $this->firebase_db = new FirebaseDB();
    }

    public function i()
    {
        phpinfo();
        die();
    }

    public function fs()
    {
        $data_count = rand(10, 20);
        for ($i = 1; $i <= $data_count; $i++) {
            $konnection = $this->_konnectFirestoreClient->collection('sites')->document(sha1(rand() . time()));
            $konnection->set([
                'user_id' => (int) date('YzHisu'),
                'ip_address' => @$_SERVER['REMOTE_ADDR'],
                'added' => date('F d, Y H:i:s'),
                'timezone_added' => date('F d, Y H:i:s') . ' - ' . date('e'),
            ]);
        }
        die('!');
    }

    public function fr()
    {
        // $konnection = $this->_konnectFirestoreClient->collection('sites');
        // $docs = $konnection->orderBy('added', 'desc')->limit(10)->documents();
        // $_logins = [];
        // foreach ($docs as $doc) {
        //     if ($doc->exists()) {
        //         $_logins[] = $doc->data();
        //     }
        // }
        // return $_logins;

        return $this->firebase_db->fr();
    }

    public function post(Request $request)
    {
        $body = file_get_contents('php://input');
        $form = $request->all();
        $collexn = 'core';
        if (isset($form['type']) && strlen(trim($form['type']))) {
            $collexn = preg_replace('/[^a-zA-Z-]+/', '', $form['type']);
        }
        unset($form['type']);
        $post_data = [
            'id' => (new \DateTime())->format('YzHisu'),
            'body' => json_encode($body),
            'form' => json_encode($form),
            'ip_address' => @$_SERVER['REMOTE_ADDR'],
            'datetime' => date('F d, Y H:i:s'),
            'timezone' => date('F d, Y H:i:s') . ' - ' . date('e'),
        ];
        $konnection = $this->_konnectFirestoreClient->collection("$collexn")->document($post_data['id']);
        $konnection->set($post_data);
        unset($post_data['ip_address'], $post_data['id']);
        return [
            'collekshn' => $collexn,
            'data' => $post_data
        ];
    }

    public function get(Request $request)
    {
        $form = $request->all();
        $collexn = 'core';
        if (isset($form['type']) && strlen(trim($form['type']))) {
            $collexn = preg_replace('/[^a-zA-Z-]+/', '', $form['type']);
        }
        $konnection = $this->_konnectFirestoreClient->collection($collexn);
        $docs = $konnection->orderBy('id', 'desc')->limit(20)->documents();
        $_logins = [];
        foreach ($docs as $doc) {
            $_logins[] = $doc->data();
        }
        return $_logins;
    }
}
