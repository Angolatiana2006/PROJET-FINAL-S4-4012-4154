<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class SqliteTest extends Controller
{
    public function index()
    {
        try {
            $db = \Config\Database::connect();
            
            // Récupérer les données
            $data = [
                'version' => $this->getSqliteVersion($db),
                'tables' => $this->getTables($db),
                'users' => $this->getUsers($db),
                'status' => 'success'
            ];
            
            return view('sqlite_test', $data);
            
        } catch (\Exception $e) {
            $data = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
            return view('sqlite_test', $data);
        }
    }
    
    private function getSqliteVersion($db)
    {
        $result = $db->query('SELECT SQLITE_VERSION() as version');
        return $result->getRow()->version;
    }
    
    private function getTables($db)
    {
        $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
        return $result->getResult();
    }
    
    private function getUsers($db)
    {
        $result = $db->query('SELECT * FROM users');
        return $result->getResult();
    }
}