<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentModel extends Model
{
    protected $table = 'documents';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'titre',
        'chemin_fichier',
        'type_fichier',
        'taille_fichier',
        'dossier_id',
        'created_by',
        'is_deleted',
        'created_at',
    ];

    protected $useTimestamps = false;
}