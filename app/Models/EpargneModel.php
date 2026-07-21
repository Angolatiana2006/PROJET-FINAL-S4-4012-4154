<?php

namespace App\Models;

use CodeIgniter\Model;

class EpargneModel extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $allowedFields = ['epargne', 'pourcentage_epargne'];
    protected $userTimesTamps = false;

    public function getEpargneByClients($clientId)
    {

        return $this->select('epargne', 'pourcentage_epargne')
                    ->where('id', $clientId)
                    ->first();
    }

    public function updatePourcentage($clientId, $pourcentage)
    {

        return $this->update($clientId, ['pourcentage_epargne' => $pourcentage]);
        
    }

     public function crediterEpargne($clientId, $montant)
    {

        return $this->set('epargne', "epargne + {$montant}", false )
                    ->where('id', $clientId)
                    ->update();
    }

     public function debiterEpargne($clientId, $montant)
    {

        return $this->set('epargne', "epargne + {$montant}", false )
                    ->where('id', $clientId)
                    ->update();
    }

     public function getTotalEpargne($clientId)
    {

        $result = $this->select('epargne')->where('id', $clientId)->first();
        return $result ? $result['epargne'] : 0;
    }

      public function getPOurcentage($clientId)
    {

        $result = $this->select('pourcentage_epargne')->where('id', $clientId)->first();
        return $result ? (float) $result['pourcentage_epargne'] : 0;
    }

      public function calculerMontantEpargne($montant, $pourcentage)
    {

        return $montant * ($pourcentage / 100);
    }

}