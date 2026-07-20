<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password', 'role'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Rôles disponibles
    const ROLE_ADMIN = 'admin';
    const ROLE_CLIENT = 'client';

    /**
     * Authentifie un utilisateur
     */
    public function authenticate($username, $password)
    {
        $user = $this->where('username', $username)->first();
        
        if ($user) {
            // Comparaison directe (pour le moment, mais à remplacer par password_hash ensuite)
            if ($password === $user['password']) {
                return $user;
            }
        }
        
        return null;
    }

    /**
     * Vérifie si l'utilisateur est admin
     */
    public function isAdmin($userId)
    {
        $user = $this->find($userId);
        return $user && $user['role'] === self::ROLE_ADMIN;
    }

    /**
     * Récupère le rôle d'un utilisateur
     */
    public function getRole($userId)
    {
        $user = $this->find($userId);
        return $user ? $user['role'] : null;
    }

    /**
     * Vérifie si un username existe déjà
     */
    public function usernameExists($username)
    {
        return $this->where('username', $username)->countAllResults() > 0;
    }
}