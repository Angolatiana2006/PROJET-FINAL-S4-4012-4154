<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClientModel;
use App\Models\TransactionModel;

class ClientsController extends BaseController
{
    protected $clientModel;
    protected $transactionModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->transactionModel = new TransactionModel();
    }

    /**
     * Liste des clients
     */
    public function index()
    {
        $data = [
            'title' => 'Gestion des clients',
            'header_title' => 'Situation des comptes clients',
         
            'clients' => $this->clientModel->getClientsWithStats(),
            'stats' => $this->clientModel->getGlobalStats(),
            'mostActive' => $this->clientModel->getMostActive(5),
            'richest' => $this->clientModel->getRichest(5),
        ];

        return view('admin/clients/index', $data);
    }

    /**
     * Détails d'un client
     */
    public function show($id)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            return redirect()->to('/admin/clients')
                           ->with('error', 'Client introuvable');
        }

        // Récupérer les transactions du client
        $db = \Config\Database::connect();
        $transactions = $db->table('transactions')
                           ->where('sender_id', $id)
                           ->orWhere('receiver_id', $id)
                           ->orderBy('created_at', 'DESC')
                           ->limit(20)
                           ->get()
                           ->getResultArray();

        // Statistiques du client
        $stats = [
            'total_transactions' => $this->clientModel->getTransactionCount($id),
            'total_fees' => $this->clientModel->getTotalFees($id),
            'last_transaction' => $this->clientModel->getLastTransaction($id),
        ];

        $data = [
            'title' => 'Détails du client',
            'header_title' => 'Client : ' . $client['full_name'],
            'header_actions' => '
                <a href="'.base_url('admin/clients').'" class="btn btn-white">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
                <a href="'.base_url('admin/clients/edit/'.$id).'" class="btn btn-white">
                    <i class="fas fa-pen"></i> Modifier
                </a>
            ',
            'client' => $client,
            'transactions' => $transactions,
            'stats' => $stats,
        ];

        return view('admin/clients/show', $data);
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $data = [
            'title' => 'Nouveau client',
            'header_title' => 'Ajouter un client',
            'header_actions' => '
                <a href="'.base_url('admin/clients').'" class="btn btn-white">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            ',
        ];

        return view('admin/clients/create', $data);
    }

    /**
     * Enregistre un nouveau client
     */
    public function store()
    {
        $rules = [
            'msisdn' => 'required|min_length[10]|max_length[20]|is_unique[clients.msisdn]',
            'full_name' => 'required|min_length[3]|max_length[100]',
            'email' => 'permit_empty|valid_email',
            'balance' => 'permit_empty|numeric|greater_than_equal_to[0]',
            'pin_code' => 'required|min_length[4]|max_length[10]',
            'status' => 'required|in_list[active,suspended,blocked]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->with('errors', $this->validator->getErrors())
                           ->withInput();
        }

        $data = [
            'msisdn' => $this->request->getPost('msisdn'),
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'balance' => $this->request->getPost('balance') ?? 0,
            'pin_code' => $this->request->getPost('pin_code'),
            'status' => $this->request->getPost('status'),
        ];

        if ($this->clientModel->insert($data)) {
            return redirect()->to('/admin/clients')
                           ->with('success', 'Client ajouté avec succès');
        }

        return redirect()->back()
                       ->with('error', 'Erreur lors de l\'ajout')
                       ->withInput();
    }

    /**
     * Formulaire d'édition
     */
    public function edit($id)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            return redirect()->to('/admin/clients')
                           ->with('error', 'Client introuvable');
        }

        $data = [
            'title' => 'Modifier le client',
            'header_title' => 'Modifier : ' . $client['full_name'],
            'header_actions' => '
                <a href="'.base_url('admin/clients').'" class="btn btn-white">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            ',
            'client' => $client,
        ];

        return view('admin/clients/edit', $data);
    }

    /**
     * Met à jour un client
     */
    public function update($id)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            return redirect()->to('/admin/clients')
                           ->with('error', 'Client introuvable');
        }

        $rules = [
            'msisdn' => 'required|min_length[10]|max_length[20]',
            'full_name' => 'required|min_length[3]|max_length[100]',
            'email' => 'permit_empty|valid_email',
            'balance' => 'permit_empty|numeric|greater_than_equal_to[0]',
            'pin_code' => 'required|min_length[4]|max_length[10]',
            'status' => 'required|in_list[active,suspended,blocked]',
        ];

        // Vérifier l'unicité du MSISDN
        if ($this->request->getPost('msisdn') !== $client['msisdn']) {
            $rules['msisdn'] .= '|is_unique[clients.msisdn]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->with('errors', $this->validator->getErrors())
                           ->withInput();
        }

        $data = [
            'msisdn' => $this->request->getPost('msisdn'),
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'balance' => $this->request->getPost('balance'),
            'pin_code' => $this->request->getPost('pin_code'),
            'status' => $this->request->getPost('status'),
        ];

        if ($this->clientModel->update($id, $data)) {
            return redirect()->to('/admin/clients')
                           ->with('success', 'Client mis à jour avec succès');
        }

        return redirect()->back()
                       ->with('error', 'Erreur lors de la mise à jour')
                       ->withInput();
    }

    /**
     * Supprime un client
     */
    public function delete($id)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Client introuvable'
            ]);
        }

        if ($this->clientModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Client supprimé avec succès'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Erreur lors de la suppression'
        ]);
    }

    /**
     * Active/Désactive un client
     */
    public function toggle($id)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Client introuvable'
            ]);
        }

        if ($this->clientModel->toggleStatus($id)) {
            $newStatus = $client['status'] === 'active' ? 'suspended' : 'active';
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Statut modifié avec succès',
                'new_status' => $newStatus
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Erreur lors du changement de statut'
        ]);
    }

    /**
     * Exporte les clients en CSV
     */
    public function export()
    {
        $clients = $this->clientModel->findAll();

        $filename = "clients_" . date('Y-m-d') . ".csv";
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Entête
        fputcsv($output, [
            'ID', 'MSISDN', 'Nom complet', 'Email', 
            'Solde (Ar)', 'PIN', 'Statut', 'Date création'
        ]);
        
        // Données
        foreach ($clients as $client) {
            fputcsv($output, [
                $client['id'],
                $client['msisdn'],
                $client['full_name'],
                $client['email'],
                $client['balance'],
                $client['pin_code'],
                $client['status'],
                $client['created_at']
            ]);
        }
        
        fclose($output);
        exit;
    }

    /**
     * API - Recherche de clients
     */
    public function search()
    {
        $keyword = $this->request->getGet('q');
        
        if (!$keyword) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Mot-clé requis'
            ]);
        }

        $clients = $this->clientModel->search($keyword);
        
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $clients
        ]);
    }
}