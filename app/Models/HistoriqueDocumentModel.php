<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoriqueDocumentModel extends Model
{
    protected $table = 'v_historique_documents';
    protected $primaryKey       = 'historique_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'historique_id',
        'user_id',
        'utilisateur_nom',
        'utilisateur_email',
        'document_id',
        'document_titre',
        'action',
        'details',
        'created_at',
    ];

    protected $useTimestamps = false;
}