<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PrefixModel;

class ExternalOperatorController extends BaseController
{
    protected $prefixModel;

    public function __construct()
    {
        $this->prefixModel = new PrefixModel();
    }

   public function index()
{
    $data = [
        'title' => 'Opérateurs externes',
        'header_title' => 'Gestion des opérateurs externes',
        'header_actions' => '
            <a href="'.base_url('admin/external-operators/create').'" class="btn btn-white">
                <i class="fas fa-plus-circle"></i> Ajouter
            </a>
        ',
        'operators' => $this->prefixModel->getExternalPrefixes(), // UNIQUEMENT externes
        'stats' => $this->prefixModel->getStats(),
    ];

    return view('admin/external_operators/index', $data);
}

    public function create()
    {
        $data = [
            'title' => 'Ajouter un opérateur externe',
            'header_title' => 'Nouvel opérateur externe',
            'header_actions' => '
                <a href="'.base_url('admin/external-operators').'" class="btn btn-white">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            ',
        ];

        return view('admin/external_operators/create', $data);
    }

    public function store()
    {
        $rules = [
            'prefix' => 'required|min_length[3]|max_length[3]|is_unique[prefixes.prefix]',
            'operator_name' => 'required|min_length[2]|max_length[50]',
            'external_fee_percent' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->with('errors', $this->validator->getErrors())
                           ->withInput();
        }

        $data = [
            'prefix' => $this->request->getPost('prefix'),
            'operator_name' => $this->request->getPost('operator_name'),
            'is_active' => 1,
            'is_external' => 1,
            'external_fee_percent' => $this->request->getPost('external_fee_percent'),
            'external_min_fee' => $this->request->getPost('external_min_fee') ?? 0,
            'external_max_fee' => $this->request->getPost('external_max_fee') ?? 0,
        ];

        if ($this->prefixModel->insert($data)) {
            return redirect()->to('/admin/external-operators')
                           ->with('success', 'Opérateur externe ajouté avec succès');
        }

        return redirect()->back()
                       ->with('error', 'Erreur lors de l\'ajout')
                       ->withInput();
    }

    public function edit($id)
    {
        $operator = $this->prefixModel->find($id);
        
        if (!$operator || !$operator['is_external']) {
            return redirect()->to('/admin/external-operators')
                           ->with('error', 'Opérateur externe introuvable');
        }

        $data = [
            'title' => 'Modifier un opérateur externe',
            'header_title' => 'Modifier : ' . $operator['operator_name'],
            'header_actions' => '
                <a href="'.base_url('admin/external-operators').'" class="btn btn-white">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            ',
            'operator' => $operator,
        ];

        return view('admin/external_operators/edit', $data);
    }

    public function update($id)
    {
        $operator = $this->prefixModel->find($id);
        
        if (!$operator || !$operator['is_external']) {
            return redirect()->to('/admin/external-operators')
                           ->with('error', 'Opérateur externe introuvable');
        }

        $rules = [
            'prefix' => 'required|min_length[3]|max_length[3]',
            'operator_name' => 'required|min_length[2]|max_length[50]',
            'external_fee_percent' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        ];

        if ($this->request->getPost('prefix') !== $operator['prefix']) {
            $rules['prefix'] .= '|is_unique[prefixes.prefix]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->with('errors', $this->validator->getErrors())
                           ->withInput();
        }

        $data = [
            'prefix' => $this->request->getPost('prefix'),
            'operator_name' => $this->request->getPost('operator_name'),
            'external_fee_percent' => $this->request->getPost('external_fee_percent'),
            'external_min_fee' => $this->request->getPost('external_min_fee') ?? 0,
            'external_max_fee' => $this->request->getPost('external_max_fee') ?? 0,
        ];

        if ($this->prefixModel->update($id, $data)) {
            return redirect()->to('/admin/external-operators')
                           ->with('success', 'Opérateur externe modifié avec succès');
        }

        return redirect()->back()
                       ->with('error', 'Erreur lors de la modification')
                       ->withInput();
    }

    public function delete($id)
    {
        $operator = $this->prefixModel->find($id);
        
        if (!$operator || !$operator['is_external']) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Opérateur externe introuvable'
            ]);
        }

        if ($this->prefixModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Opérateur externe supprimé avec succès'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Erreur lors de la suppression'
        ]);
    }

    public function toggle($id)
    {
        $operator = $this->prefixModel->find($id);
        
        if (!$operator || !$operator['is_external']) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Opérateur externe introuvable'
            ]);
        }

        if ($this->prefixModel->toggleStatus($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Statut modifié avec succès',
                'new_status' => !$operator['is_active']
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Erreur lors du changement de statut'
        ]);
    }
}