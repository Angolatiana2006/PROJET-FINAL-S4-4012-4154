<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\TransactionModel;
use App\Models\FeeConfigModel;

class ClientController extends BaseController
{
    protected $clientModel;
    protected $transactionModel;
    protected $feeModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->transactionModel = new TransactionModel();
        $this->feeModel = new FeeConfigModel();
    }

    /**
     * Page de connexion client
     */
    public function login()
    {
        return view('client/login');
    }

    /**
     * Traite la connexion client
     */
    public function doLogin()
    {
        $msisdn = $this->request->getPost('msisdn');

        if (empty($msisdn)) {
            return redirect()->back()->with('error', 'Veuillez entrer votre numéro de téléphone');
        }

        $msisdn = preg_replace('/[^0-9]/', '', $msisdn);
        $client = $this->clientModel->where('msisdn', $msisdn)->first();

        if (!$client) {
            return redirect()->back()->with('error', 'Numéro de téléphone non trouvé');
        }

        if ($client['status'] !== 'active') {
            return redirect()->back()->with('error', 'Votre compte est suspendu. Contactez l\'administrateur.');
        }

        session()->set([
            'client_id' => $client['id'],
            'client_msisdn' => $client['msisdn'],
            'client_name' => $client['full_name'],
            'client_balance' => $client['balance'],
            'is_client_logged_in' => true,
        ]);

        return redirect()->to('/client/dashboard')->with('success', 'Bienvenue ' . $client['full_name']);
    }

    /**
     * Dashboard client - CORRIGÉ
     */
    public function dashboard()
    {
        if (!session()->has('client_id')) {
            return redirect()->to('/client/login');
        }

        $clientId = session()->get('client_id');
        $client = $this->clientModel->find($clientId);

        // Récupérer les transactions du client
        $db = \Config\Database::connect();
        
        // 1. Transactions où le client est expéditeur OU destinataire
        $recentTransactions = $db->table('transactions')
                                 ->where('sender_id', $clientId)
                                 ->orWhere('receiver_id', $clientId)
                                 ->orderBy('created_at', 'DESC')
                                 ->limit(10)
                                 ->get()
                                 ->getResultArray();

        // 2. Total envoyé (expéditeur)
        $totalSent = $db->table('transactions')
                        ->select('SUM(amount + fee_amount) as total')
                        ->where('sender_id', $clientId)
                        ->where('status', 'completed')
                        ->get()
                        ->getRowArray()['total'] ?? 0;

        // 3. Total reçu (destinataire)
        $totalReceived = $db->table('transactions')
                            ->select('SUM(amount) as total')
                            ->where('receiver_id', $clientId)
                            ->where('status', 'completed')
                            ->get()
                            ->getRowArray()['total'] ?? 0;

        // 4. Vérification des transactions
        $countTransactions = count($recentTransactions);
        log_message('debug', "Client $clientId - Transactions trouvées: $countTransactions");

        $data = [
            'title' => 'Dashboard Client',
            'client' => $client,
            'recentTransactions' => $recentTransactions,
            'totalSent' => $totalSent,
            'totalReceived' => $totalReceived,
        ];

        return view('client/dashboard', $data);
    }

    // ============================================
    // AUTRES MÉTHODES (deposit, withdrawal, transfer, history...)
    // ============================================

    public function deposit()
    {
        if (!session()->has('client_id')) {
            return redirect()->to('/client/login');
        }

        $clientId = session()->get('client_id');
        $client = $this->clientModel->find($clientId);

        $data = [
            'title' => 'Dépôt d\'argent',
            'client' => $client,
        ];

        return view('client/deposit', $data);
    }

    public function doDeposit()
    {
        if (!session()->has('client_id')) {
            return redirect()->to('/client/login');
        }

        $clientId = session()->get('client_id');
        $amount = (float) $this->request->getPost('amount');

        if (empty($amount) || $amount <= 0) {
            return redirect()->back()->with('error', 'Montant invalide');
        }

        $fee = $this->feeModel->getFee('deposit', $amount);
        $totalAmount = $amount + $fee;

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $transaction = [
                'transaction_id' => $this->transactionModel->generateTransactionId(),
                'sender_id' => null,
                'receiver_id' => $clientId,
                'operation_type' => 'deposit',
                'amount' => $amount,
                'fee_amount' => $fee,
                'total_amount' => $totalAmount,
                'status' => 'completed',
                'description' => 'Dépôt automatique',
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $this->transactionModel->insert($transaction);

            $this->clientModel->updateBalance($clientId, $totalAmount);

            $db->transComplete();

            $client = $this->clientModel->find($clientId);
            session()->set('client_balance', $client['balance']);

            return redirect()->to('/client/dashboard')->with('success', 'Dépôt de ' . number_format($amount, 0) . ' Ar effectué avec succès');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Erreur lors du dépôt: ' . $e->getMessage());
        }
    }

    public function withdrawal()
    {
        if (!session()->has('client_id')) {
            return redirect()->to('/client/login');
        }

        $clientId = session()->get('client_id');
        $client = $this->clientModel->find($clientId);

        $data = [
            'title' => 'Retrait d\'argent',
            'client' => $client,
        ];

        return view('client/withdrawal', $data);
    }

    public function doWithdrawal()
    {
        if (!session()->has('client_id')) {
            return redirect()->to('/client/login');
        }

        $clientId = session()->get('client_id');
        $amount = (float) $this->request->getPost('amount');

        if (empty($amount) || $amount <= 0) {
            return redirect()->back()->with('error', 'Montant invalide');
        }

        $fee = $this->feeModel->getFee('withdrawal', $amount);
        $totalAmount = $amount + $fee;

        $client = $this->clientModel->find($clientId);

        if ($client['balance'] < $totalAmount) {
            return redirect()->back()->with('error', 'Solde insuffisant. Vous avez ' . number_format($client['balance'], 0) . ' Ar');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $transaction = [
                'transaction_id' => $this->transactionModel->generateTransactionId(),
                'sender_id' => $clientId,
                'receiver_id' => null,
                'operation_type' => 'withdrawal',
                'amount' => $amount,
                'fee_amount' => $fee,
                'total_amount' => $totalAmount,
                'status' => 'completed',
                'description' => 'Retrait automatique',
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $this->transactionModel->insert($transaction);

            $this->clientModel->updateBalance($clientId, -$totalAmount);

            $db->transComplete();

            $client = $this->clientModel->find($clientId);
            session()->set('client_balance', $client['balance']);

            return redirect()->to('/client/dashboard')->with('success', 'Retrait de ' . number_format($amount, 0) . ' Ar effectué avec succès');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Erreur lors du retrait: ' . $e->getMessage());
        }
    }

    public function transfer()
    {
        if (!session()->has('client_id')) {
            return redirect()->to('/client/login');
        }

        $clientId = session()->get('client_id');
        $client = $this->clientModel->find($clientId);

        $data = [
            'title' => 'Transfert d\'argent',
            'client' => $client,
        ];

        return view('client/transfer', $data);
    }

    public function doTransfer()
    {
        if (!session()->has('client_id')) {
            return redirect()->to('/client/login');
        }

        $clientId = session()->get('client_id');
        $receiverMsisdn = $this->request->getPost('receiver_msisdn');
        $amount = (float) $this->request->getPost('amount');

        if (empty($receiverMsisdn)) {
            return redirect()->back()->with('error', 'Veuillez entrer le numéro du destinataire');
        }

        if (empty($amount) || $amount <= 0) {
            return redirect()->back()->with('error', 'Montant invalide');
        }

        $receiverMsisdn = preg_replace('/[^0-9]/', '', $receiverMsisdn);

        $client = $this->clientModel->find($clientId);
        if ($client['msisdn'] === $receiverMsisdn) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous transférer à vous-même');
        }

        $receiver = $this->clientModel->where('msisdn', $receiverMsisdn)->first();
        if (!$receiver) {
            return redirect()->back()->with('error', 'Numéro du destinataire introuvable');
        }

        if ($receiver['status'] !== 'active') {
            return redirect()->back()->with('error', 'Le compte du destinataire est suspendu');
        }

        $fee = $this->feeModel->getFee('transfer', $amount);
        $totalAmount = $amount + $fee;

        if ($client['balance'] < $totalAmount) {
            return redirect()->back()->with('error', 'Solde insuffisant. Vous avez ' . number_format($client['balance'], 0) . ' Ar');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $transaction = [
                'transaction_id' => $this->transactionModel->generateTransactionId(),
                'sender_id' => $clientId,
                'receiver_id' => $receiver['id'],
                'operation_type' => 'transfer',
                'amount' => $amount,
                'fee_amount' => $fee,
                'total_amount' => $totalAmount,
                'status' => 'completed',
                'description' => 'Transfert vers ' . $receiver['msisdn'],
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $this->transactionModel->insert($transaction);

            $this->clientModel->updateBalance($clientId, -$totalAmount);
            $this->clientModel->updateBalance($receiver['id'], $amount);

            $db->transComplete();

            $client = $this->clientModel->find($clientId);
            session()->set('client_balance', $client['balance']);

            return redirect()->to('/client/dashboard')->with('success', 'Transfert de ' . number_format($amount, 0) . ' Ar vers ' . $receiverMsisdn . ' effectué avec succès');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Erreur lors du transfert: ' . $e->getMessage());
        }
    }

    public function history()
    {
        if (!session()->has('client_id')) {
            return redirect()->to('/client/login');
        }

        $clientId = session()->get('client_id');
        $db = \Config\Database::connect();

        $transactions = $db->table('transactions')
                           ->where('sender_id', $clientId)
                           ->orWhere('receiver_id', $clientId)
                           ->orderBy('created_at', 'DESC')
                           ->limit(50)
                           ->get()
                           ->getResultArray();

        $data = [
            'title' => 'Historique des transactions',
            'transactions' => $transactions,
            'clientId' => $clientId,
        ];

        return view('client/history', $data);
    }

    public function logout()
    {
        session()->remove(['client_id', 'client_msisdn', 'client_name', 'client_balance', 'is_client_logged_in']);
        return redirect()->to('/client/login')->with('success', 'Vous êtes déconnecté');
    }

    /**
     * Vérification des transactions (DEBUG)
     */
    public function debugTransactions()
    {
        if (!session()->has('client_id')) {
            return redirect()->to('/client/login');
        }

        $clientId = session()->get('client_id');
        $db = \Config\Database::connect();

        echo "<h2>Debug Transactions</h2>";
        echo "<h3>Client ID: $clientId</h3>";

        // Toutes les transactions
        $all = $db->table('transactions')->get()->getResultArray();
        echo "<h4>Toutes les transactions (" . count($all) . "):</h4>";
        echo "<pre>";
        print_r($all);
        echo "</pre>";

        // Transactions du client
        $clientTxs = $db->table('transactions')
                        ->where('sender_id', $clientId)
                        ->orWhere('receiver_id', $clientId)
                        ->get()
                        ->getResultArray();
        
        echo "<h4>Transactions du client (" . count($clientTxs) . "):</h4>";
        echo "<pre>";
        print_r($clientTxs);
        echo "</pre>";
    }
}