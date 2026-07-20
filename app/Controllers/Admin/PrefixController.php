<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PrefixModel;

class PrefixController extends BaseController
{
    protected $prefixModel;

    public function __construct()
    {
        $this->prefixModel = new PrefixModel();
    }

   public function index()
{
    $data = [
        'prefixes' => $this->prefixModel->getActivePrefixes(), // UNIQUEMENT internes
        'stats' => $this->prefixModel->getStats(),
        'title' => 'Gestion des préfixes',
    ];

    return view('admin/prefixes/index', $data);
}

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $data = [
            'title' => 'Ajouter un préfixe',
        ];

        return view('admin/prefixes/create', $data);
    }

    /**
     * Enregistre un nouveau préfixe
     */
    public function store()
    {
        // Validation des règles
        $rules = [
            'prefix' => 'required|min_length[3]|max_length[3]|is_unique[prefixes.prefix]',
            'operator_name' => 'required|min_length[2]|max_length[50]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->with('errors', $this->validator->getErrors())
                           ->withInput();
        }

        // Validation supplémentaire (3 chiffres)
        $prefix = $this->request->getPost('prefix');
        if (!$this->prefixModel->validatePrefix($prefix)) {
            return redirect()->back()
                           ->with('error', 'Le préfixe doit contenir 3 chiffres')
                           ->withInput();
        }

        // Préparation des données
        $data = [
            'prefix' => $prefix,
            'operator_name' => $this->request->getPost('operator_name'),
            'is_active' => $this->request->getPost('is_active') ? true : false,
        ];

        // Insertion
        if ($this->prefixModel->insert($data)) {
            return redirect()->to('/admin/prefixes')
                           ->with('success', 'Préfixe ajouté avec succès !');
        } else {
            return redirect()->back()
                           ->with('error', 'Erreur lors de l\'ajout du préfixe')
                           ->withInput();
        }
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit($id)
    {
        $prefix = $this->prefixModel->find($id);
        
        if (!$prefix) {
            return redirect()->to('/admin/prefixes')
                           ->with('error', 'Préfixe introuvable');
        }

        $data = [
            'prefix' => $prefix,
            'title' => 'Modifier le préfixe',
        ];

        return view('admin/prefixes/edit', $data);
    }

    /**
     * Met à jour un préfixe
     */
    public function update($id)
    {
        // Vérifier si le préfixe existe
        $existingPrefix = $this->prefixModel->find($id);
        if (!$existingPrefix) {
            return redirect()->to('/admin/prefixes')
                           ->with('error', 'Préfixe introuvable');
        }

        // Validation des règles
        $rules = [
            'prefix' => 'required|min_length[3]|max_length[3]',
            'operator_name' => 'required|min_length[2]|max_length[50]',
        ];

        // Vérifier l'unicité du préfixe (sauf pour le même ID)
        $prefix = $this->request->getPost('prefix');
        if ($prefix !== $existingPrefix['prefix']) {
            $rules['prefix'] .= '|is_unique[prefixes.prefix]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->with('errors', $this->validator->getErrors())
                           ->withInput();
        }

        // Validation supplémentaire
        if (!$this->prefixModel->validatePrefix($prefix)) {
            return redirect()->back()
                           ->with('error', 'Le préfixe doit contenir 3 chiffres')
                           ->withInput();
        }

        // Préparation des données
        $data = [
            'prefix' => $prefix,
            'operator_name' => $this->request->getPost('operator_name'),
            'is_active' => $this->request->getPost('is_active') ? true : false,
        ];

        // Mise à jour
        if ($this->prefixModel->update($id, $data)) {
            return redirect()->to('/admin/prefixes')
                           ->with('success', 'Préfixe mis à jour avec succès !');
        } else {
            return redirect()->back()
                           ->with('error', 'Erreur lors de la mise à jour')
                           ->withInput();
        }
    }

    /**
     * Supprime un préfixe
     */
    public function delete($id)
    {
        $prefix = $this->prefixModel->find($id);
        
        if (!$prefix) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Préfixe introuvable'
            ]);
        }

        if ($this->prefixModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Préfixe supprimé avec succès'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Erreur lors de la suppression'
            ]);
        }
    }

    /**
     * Active/Désactive un préfixe (Toggle)
     */
    public function toggle($id)
    {
        $prefix = $this->prefixModel->find($id);
        
        if (!$prefix) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Préfixe introuvable'
            ]);
        }

        if ($this->prefixModel->toggleStatus($id)) {
            $newStatus = $prefix['is_active'] ? 'désactivé' : 'activé';
            return $this->response->setJSON([
                'status' => 'success',
                'message' => "Préfixe $newStatus avec succès",
                'new_status' => !$prefix['is_active']
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Erreur lors du changement de statut'
            ]);
        }
    }

    /**
     * Vérifie la validité d'un préfixe (API)
     */
    public function checkPrefix()
    {
        $prefix = $this->request->getGet('prefix');
        
        if (!$prefix) {
            return $this->response->setJSON([
                'valid' => false,
                'message' => 'Préfixe requis'
            ]);
        }

        $isValid = $this->prefixModel->isValidPrefix($prefix);
        
        return $this->response->setJSON([
            'valid' => $isValid,
            'prefix' => $prefix,
            'message' => $isValid ? 'Préfixe valide' : 'Préfixe invalide'
        ]);
    }
}