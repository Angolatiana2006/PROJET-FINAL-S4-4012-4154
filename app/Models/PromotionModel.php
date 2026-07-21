<?php
namespace App\Models;

use CodeIgniter\Model;

class PromotionModel extends Model{

    protected $table = 'promotions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom','pourcentage','actif'];
    protected $usetimestamps = true;

    public function getActivePromotion(){
        return $this->where('actif',1) -> first();
    }

    public function getPourcentage(){
        $promo = $this->getActivePromotion();
        if($promo){
            return(float) $promo['pourcentage'];
        }
        return 0;
    }

    public function calculerPromotion($montant){
        $pourcentage = $this -> getPourcentage();
        if($pourcentage > 0) {
            return $montant * ($pourcentage / 100);
        }
        return 0;
    }
}
?>