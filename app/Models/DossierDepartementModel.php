<?php

namespace App\Models;

use CodeIgniter\Model;

class DossierDepartementModel extends Model
{
    protected $table = 'v_dossiers_departement';
    protected $primaryKey       = 'departement_id ';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'departement_id ',
        'departement_nom',
        'dossier_id',
        'dossier_nom',
        'dossier_parent_id',
        'document_id',
        'document_titre',
        'document_chemin_fichier',
    ];

    protected $useTimestamps = false;
}