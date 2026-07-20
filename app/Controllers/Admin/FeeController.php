<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FeeConfigModel;

class FeeController extends BaseController
{
    protected $feeModel;

    public function __construct()
    {
        $this->feeModel = new FeeConfigModel();
    }

    /**
     * Affiche la liste des barèmes de frais
     */
    public function index()
    {
        $types = $this->feeModel->getTypes();
        $data = [
            'title' => 'Gestion des barèmes de frais',
            'header_title' => 'Barèmes de frais',
            'header_actions' => '<a href="'.base_url('admin/fees/create').'" class="btn btn-white"><i class="fas fa-plus-circle"></i> Nouveau barème</a>',
            'types' => $types,
            'stats' => $this->feeModel->getStatsByType(),
        ];

        // Récupérer les configurations par type
        foreach (array_keys($types) as $type) {
            $data['configs'][$type] = $this->feeModel->getByType($type);
        }

        return view('admin/fees/index', $data);
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $data = [
            'title' => 'Ajouter un barème',
            'header_title' => 'Nouveau barème',
            'header_actions' => '<a href="'.base_url('admin/fees').'" class="btn btn-white"><i class="fas fa-arrow-left"></i> Retour</a>',
            'types' => $this->feeModel->getTypes(),
        ];

        return view('admin/fees/create', $data);
    }

    /**
     * Enregistre un nouveau barème
     */
    public function store()
    {
        $rules = [
            'operation_type' => 'required|in_list[deposit,withdrawal,transfer]',
            'min_amount' => 'required|numeric|greater_than_equal_to[0]',
            'max_amount' => 'required|numeric|greater_than[min_amount]',
            'fee_amount' => 'required|numeric|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->with('errors', $this->validator->getErrors())
                           ->withInput();
        }

        $data = [
            'operation_type' => $this->request->getPost('operation_type'),
            'min_amount' => $this->request->getPost('min_amount'),
            'max_amount' => $this->request->getPost('max_amount'),
            'fee_amount' => $this->request->getPost('fee_amount'),
            'is_active' => $this->request->getPost('is_active') ? true : false,
        ];

        // Validation supplémentaire
        $errors = $this->feeModel->validateData($data);
        if (!empty($errors)) {
            return redirect()->back()
                           ->with('errors', $errors)
                           ->withInput();
        }

        if ($this->feeModel->insert($data)) {
            return redirect()->to('/admin/fees')
                           ->with('success', 'Barème ajouté avec succès');
        }

        return redirect()->back()
                       ->with('error', 'Erreur lors de l\'ajout')
                       ->withInput();
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit($id)
    {
        $config = $this->feeModel->find($id);
        
        if (!$config) {
            return redirect()->to('/admin/fees')
                           ->with('error', 'Configuration introuvable');
        }

        $data = [
            'title' => 'Modifier le barème',
            'header_title' => 'Modifier barème',
            'header_actions' => '<a href="'.base_url('admin/fees').'" class="btn btn-white"><i class="fas fa-arrow-left"></i> Retour</a>',
            'config' => $config,
            'types' => $this->feeModel->getTypes(),
        ];

        return view('admin/fees/edit', $data);
    }

    /**
     * Met à jour un barème
     */
    public function update($id)
    {
        $config = $this->feeModel->find($id);
        
        if (!$config) {
            return redirect()->to('/admin/fees')
                           ->with('error', 'Configuration introuvable');
        }

        $rules = [
            'operation_type' => 'required|in_list[deposit,withdrawal,transfer]',
            'min_amount' => 'required|numeric|greater_than_equal_to[0]',
            'max_amount' => 'required|numeric|greater_than[min_amount]',
            'fee_amount' => 'required|numeric|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->with('errors', $this->validator->getErrors())
                           ->withInput();
        }

        $data = [
            'operation_type' => $this->request->getPost('operation_type'),
            'min_amount' => $this->request->getPost('min_amount'),
            'max_amount' => $this->request->getPost('max_amount'),
            'fee_amount' => $this->request->getPost('fee_amount'),
            'is_active' => $this->request->getPost('is_active') ? true : false,
        ];

        // Validation supplémentaire
        $data['id'] = $id;
        $errors = $this->feeModel->validateData($data);
        if (!empty($errors)) {
            return redirect()->back()
                           ->with('errors', $errors)
                           ->withInput();
        }

        if ($this->feeModel->update($id, $data)) {
            return redirect()->to('/admin/fees')
                           ->with('success', 'Barème mis à jour avec succès');
        }

        return redirect()->back()
                       ->with('error', 'Erreur lors de la mise à jour')
                       ->withInput();
    }

    /**
     * Supprime un barème
     */
    public function delete($id)
    {
        $config = $this->feeModel->find($id);
        
        if (!$config) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Configuration introuvable'
            ]);
        }

        if ($this->feeModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Barème supprimé avec succès'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Erreur lors de la suppression'
        ]);
    }

    /**
     * Active/Désactive un barème
     */
    public function toggle($id)
    {
        $config = $this->feeModel->find($id);
        
        if (!$config) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Configuration introuvable'
            ]);
        }

        if ($this->feeModel->toggleStatus($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Statut modifié avec succès',
                'new_status' => !$config['is_active']
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Erreur lors du changement de statut'
        ]);
    }

   /**
 * Calculer les frais pour un montant (API)
 */
public function calculateFee()
{
    $operationType = $this->request->getGet('type');
    $amount = $this->request->getGet('amount');

    if (!$operationType || !$amount) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Paramètres manquants'
        ]);
    }

    $amount = (float) $amount;
    
    // Utiliser la méthode corrigée
    $fee = $this->feeModel->getFee($operationType, $amount);
    $config = $this->feeModel->getFeeConfig($operationType, $amount);

    return $this->response->setJSON([
        'status' => 'success',
        'data' => [
            'amount' => $amount,
            'fee' => (float) $fee,
            'total' => $amount + (float) $fee,
            'config' => $config
        ]
    ]);
}
}