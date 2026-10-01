<?php

namespace App\Models;

use CodeIgniter\Model;

class UserDocPermissionModel extends Model
{
    protected $table = 'user_document_permissions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'id',
        'document_id',
        'user_id',
        'can_view',
        'can_edit',
        'can_delete',
    ];
    protected $useTimestamps = false;

    

}

  
