<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentPermissionModel extends Model
{
    protected $table = 'document_permissions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'document_id',
        'departement_id',
        'can_view',
        'can_edit',
        'can_delete',
    ];
    protected $useTimestamps = false;

}

  
