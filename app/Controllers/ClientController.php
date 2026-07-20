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
    $amount = $this->request->getPost('amount');
    
    // Nettoyer le montant (enlever tout sauf les chiffres)
    $amount = preg_replace('/[^0-9]/', '', $amount);
    $amount = (float) $amount;

    if (empty($amount) || $amount <= 0) {
        return redirect()->back()->with('error', 'Montant invalide');
    }

    // Calculer les frais
    $fee = $this->feeModel->getFee('deposit', $amount);
    $totalAmount = $amount + $fee;

    log_message('debug', "=== DÉPÔT : Client ID $clientId, Montant: $amount, Frais: $fee, Total: $totalAmount ===");

    $db = \Config\Database::connect();
    
    try {
        // 1. Mettre à jour le solde
        $db->query("UPDATE clients SET balance = balance + ? WHERE id = ?", [$totalAmount, $clientId]);
        
        // 2. Vérifier la mise à jour
        $client = $this->clientModel->find($clientId);
        log_message('debug', "💰 Nouveau solde: " . $client['balance']);
        
        // 3. Générer l'ID de transaction
        $transactionId = 'TXN' . date('YmdHis') . rand(100, 999);
        
        // 4. Insérer la transaction
        $db->query("INSERT INTO transactions (transaction_id, receiver_id, operation_type, amount, fee_amount, total_amount, status, description, created_at) 
                    VALUES (?, ?, 'deposit', ?, ?, ?, 'completed', 'Dépôt automatique', datetime('now'))", 
                    [$transactionId, $clientId, $amount, $fee, $totalAmount]);

        // 5. Mettre à jour la session
        $client = $this->clientModel->find($clientId);
        session()->set('client_balance', $client['balance']);

        log_message('debug', "✅ Dépôt réussi - Nouveau solde: " . $client['balance']);

        return redirect()->to('/client/dashboard')->with('success', 'Dépôt de ' . number_format($amount, 0) . ' Ar effectué avec succès');

    } catch (\Exception $e) {
        log_message('error', "❌ Erreur dépôt: " . $e->getMessage());
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
    $amount = $this->request->getPost('amount');
    
    // Nettoyer le montant (enlever tout sauf les chiffres)
    $amount = preg_replace('/[^0-9]/', '', $amount);
    $amount = (float) $amount;

    if (empty($amount) || $amount <= 0) {
        return redirect()->back()->with('error', 'Montant invalide');
    }

    // Calculer les frais
    $fee = $this->feeModel->getFee('withdrawal', $amount);
    $totalAmount = $amount + $fee;

    $client = $this->clientModel->find($clientId);

    // Vérifier le solde
    if ($client['balance'] < $totalAmount) {
        return redirect()->back()->with('error', 'Solde insuffisant. Vous avez ' . number_format($client['balance'], 0) . ' Ar');
    }

    log_message('debug', "=== RETRAIT : Client ID $clientId, Montant: $amount, Frais: $fee, Total: $totalAmount ===");

    $db = \Config\Database::connect();
    
    try {
        // 1. Mettre à jour le solde DIRECTEMENT en SQL
        $db->query("UPDATE clients SET balance = balance - ? WHERE id = ?", [$totalAmount, $clientId]);
        
        // 2. Vérifier la mise à jour
        $client = $this->clientModel->find($clientId);
        log_message('debug', "💰 Nouveau solde après retrait: " . $client['balance']);
        
        // 3. Générer l'ID de transaction
        $transactionId = 'TXN' . date('YmdHis') . rand(100, 999);
        
        // 4. Insérer la transaction
        $db->query("INSERT INTO transactions (transaction_id, sender_id, operation_type, amount, fee_amount, total_amount, status, description, created_at) 
                    VALUES (?, ?, 'withdrawal', ?, ?, ?, 'completed', 'Retrait automatique', datetime('now'))", 
                    [$transactionId, $clientId, $amount, $fee, $totalAmount]);

        // 5. Mettre à jour la session
        $client = $this->clientModel->find($clientId);
        session()->set('client_balance', $client['balance']);

        log_message('debug', "✅ Retrait réussi - Nouveau solde: " . $client['balance']);

        return redirect()->to('/client/dashboard')->with('success', 'Retrait de ' . number_format($amount, 0) . ' Ar effectué avec succès');

    } catch (\Exception $e) {
        log_message('error', "❌ Erreur retrait: " . $e->getMessage());
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
    $receiverMsisdns = $this->request->getPost('receiver_msisdn');
    $totalAmount = $this->request->getPost('total_amount');
    $includeFees = $this->request->getPost('include_fees') ? true : false;
    
    // Nettoyer le montant total
    $totalAmount = preg_replace('/[^0-9]/', '', $totalAmount);
    $totalAmount = (float) $totalAmount;
    
    if (empty($receiverMsisdns) || !is_array($receiverMsisdns)) {
        return redirect()->back()->with('error', 'Veuillez entrer au moins un destinataire');
    }

    if (empty($totalAmount) || $totalAmount <= 0) {
        return redirect()->back()->with('error', 'Montant invalide');
    }

    $client = $this->clientModel->find($clientId);
    $prefixModel = new \App\Models\PrefixModel();

    // Nettoyer les numéros
    $receivers = [];
    foreach ($receiverMsisdns as $msisdn) {
        $msisdn = preg_replace('/[^0-9]/', '', $msisdn);
        if (!empty($msisdn)) {
            $receivers[] = $msisdn;
        }
    }

    if (empty($receivers)) {
        return redirect()->back()->with('error', 'Veuillez entrer des numéros valides');
    }

    $receiverCount = count($receivers);
    $amountPerReceiver = $totalAmount / $receiverCount;

    // Vérifier les destinataires
    foreach ($receivers as $msisdn) {
        if ($client['msisdn'] === $msisdn) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous transférer à vous-même');
        }
        
        $prefix = substr($msisdn, 0, 3);
        $prefixData = $prefixModel->where('prefix', $prefix)->first();
        
        if (!$prefixData || !$prefixData['is_active']) {
            return redirect()->back()->with('error', 'Le numéro ' . $msisdn . ' n\'est pas valide (préfixe inconnu)');
        }
        
        if ($prefixData['is_external'] == 0) {
            $receiver = $this->clientModel->where('msisdn', $msisdn)->first();
            if (!$receiver) {
                return redirect()->back()->with('error', 'Le numéro interne ' . $msisdn . ' n\'existe pas dans notre système');
            }
            if ($receiver['status'] !== 'active') {
                return redirect()->back()->with('error', 'Le compte du destinataire ' . $msisdn . ' est suspendu');
            }
        }
    }

    // ============================================
    // CALCUL DU TOTAL À DÉBITER
    // ============================================
    $totalDeducted = 0;
    $totalFee = 0;
    $transactionData = [];

    foreach ($receivers as $msisdn) {
        $prefix = substr($msisdn, 0, 3);
        $prefixData = $prefixModel->where('prefix', $prefix)->first();
        
        // Frais de base
        $fee = $this->feeModel->getFee('transfer', $amountPerReceiver);
        
        // Si externe, ajouter commission
        if ($prefixData && $prefixData['is_external'] == 1) {
            $externalFee = $prefixModel->calculateExternalFee($prefix, $amountPerReceiver);
            $fee += $externalFee;
            $totalFee += $externalFee;
            
            log_message('debug', "📡 Externe $msisdn - Frais: $fee (base + $externalFee)");
        }
        
        $amountToDeduct = $amountPerReceiver + $fee;
        $totalDeducted += $amountToDeduct;
        
        $transactionData[] = [
            'msisdn' => $msisdn,
            'prefixData' => $prefixData,
            'amount' => $amountPerReceiver,
            'fee' => $fee,
            'amountToDeduct' => $amountToDeduct,
            'amountToSend' => $includeFees ? $amountPerReceiver + $fee : $amountPerReceiver,
        ];
    }

    // Vérifier le solde
    if ($client['balance'] < $totalDeducted) {
        return redirect()->back()->with('error', 'Solde insuffisant. Vous avez ' . number_format($client['balance'], 0) . ' Ar');
    }

    // ============================================
    // EXÉCUTION DE LA TRANSACTION (SANS ROLLBACK)
    // ============================================
    $db = \Config\Database::connect();
    
    try {
        // 1. Débiter l'expéditeur DIRECTEMENT
        $db->query("UPDATE clients SET balance = balance - ? WHERE id = ?", [$totalDeducted, $clientId]);
        log_message('debug', "💰 Expéditeur débité: -$totalDeducted Ar");

        // 2. Créditer les destinataires internes
        foreach ($transactionData as $data) {
            if ($data['prefixData'] && $data['prefixData']['is_external'] == 0) {
                $receiver = $this->clientModel->where('msisdn', $data['msisdn'])->first();
                if ($receiver) {
                    $db->query("UPDATE clients SET balance = balance + ? WHERE id = ?", [$data['amountToSend'], $receiver['id']]);
                    log_message('debug', "✅ Destinataire interne crédité: {$data['msisdn']} (+{$data['amountToSend']} Ar)");
                }
            } else {
                log_message('debug', "📡 Destinataire externe: {$data['msisdn']} (pas de crédit)");
            }
        }

        // 3. Insérer les transactions
        foreach ($transactionData as $data) {
            $transactionId = 'TXN' . date('YmdHis') . rand(100, 999);
            
            $receiverId = null;
            if ($data['prefixData'] && $data['prefixData']['is_external'] == 0) {
                $receiver = $this->clientModel->where('msisdn', $data['msisdn'])->first();
                if ($receiver) {
                    $receiverId = $receiver['id'];
                }
            }
            
            $db->query("INSERT INTO transactions (transaction_id, sender_id, receiver_id, operation_type, amount, fee_amount, total_amount, status, description, is_external, external_operator, created_at) 
                        VALUES (?, ?, ?, 'transfer', ?, ?, ?, 'completed', ?, ?, ?, datetime('now'))", 
                        [
                            $transactionId, 
                            $clientId, 
                            $receiverId, 
                            $data['amount'], 
                            $data['fee'], 
                            $data['amountToDeduct'], 
                            'Transfert vers ' . $data['msisdn'],
                            ($data['prefixData'] && $data['prefixData']['is_external'] == 1) ? 1 : 0,
                            ($data['prefixData'] && $data['prefixData']['is_external'] == 1) ? $data['prefixData']['operator_name'] : null
                        ]);
            
            log_message('debug', "✅ Transaction insérée: $transactionId");
        }

        // 4. Enregistrer dans external_transactions (si possible, sans bloquer)
        try {
            foreach ($transactionData as $data) {
                if ($data['prefixData'] && $data['prefixData']['is_external'] == 1) {
                    $externalModel = new \App\Models\ExternalTransactionModel();
                    
                    // Récupérer la dernière transaction insérée
                    $lastTx = $db->table('transactions')
                                 ->where('sender_id', $clientId)
                                 ->where('receiver_id', null)
                                 ->where('is_external', 1)
                                 ->orderBy('id', 'DESC')
                                 ->limit(1)
                                 ->get()
                                 ->getRowArray();
                    
                    if ($lastTx) {
                        $externalData = [
                            'transaction_id' => $lastTx['id'],
                            'sender_id' => $clientId,
                            'receiver_msisdn' => $data['msisdn'],
                            'receiver_prefix' => $data['prefixData']['prefix'],
                            'receiver_operator' => $data['prefixData']['operator_name'],
                            'amount' => $data['amount'],
                            'base_fee' => $this->feeModel->getFee('transfer', $data['amount']),
                            'external_fee' => $data['fee'] - $this->feeModel->getFee('transfer', $data['amount']),
                            'total_fee' => $data['fee'],
                            'fee_percent' => $data['prefixData']['external_fee_percent'],
                            'status' => 'completed',
                        ];
                        $externalModel->createExternalTransaction($externalData);
                        log_message('debug', "✅ External transaction enregistrée pour {$data['msisdn']}");
                    }
                }
            }
        } catch (\Exception $e) {
            log_message('error', "⚠️ Erreur external_transactions (ignorée): " . $e->getMessage());
            // On continue même si external_transactions échoue
        }

        // 5. Mettre à jour la session
        $client = $this->clientModel->find($clientId);
        session()->set('client_balance', $client['balance']);
        
        log_message('debug', "💰 Nouveau solde: " . $client['balance']);

        $message = 'Transfert de ' . number_format($totalAmount, 0) . ' Ar vers ' . $receiverCount . ' destinataire(s) effectué avec succès';
        if ($includeFees) {
            $message .= ' (frais inclus)';
        }
        
        $externalCount = 0;
        foreach ($receivers as $msisdn) {
            $prefix = substr($msisdn, 0, 3);
            $prefixData = $prefixModel->where('prefix', $prefix)->first();
            if ($prefixData && $prefixData['is_external'] == 1) {
                $externalCount++;
            }
        }
        if ($externalCount > 0) {
            $message .= ' (' . $externalCount . ' vers autre(s) opérateur(s))';
        }

        return redirect()->to('/client/dashboard')->with('success', $message);

    } catch (\Exception $e) {
        log_message('error', "❌ Erreur transfert: " . $e->getMessage());
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