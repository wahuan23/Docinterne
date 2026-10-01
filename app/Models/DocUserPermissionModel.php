<?php

namespace App\Models;

use CodeIgniter\Model;

class DocUserPermissionModel extends Model
{
    protected $table = 'v_documents_user_permission';
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
        'can_view',
        'can_edit',
        'can_delete',
    ];

    protected $useTimestamps = false;


public function getDocumentsForUser(int $userId, int $dossierId, int $departementId): array
{
    $sql = 'SELECT
                vdep.*,
                udp.user_id,
                COALESCE(udp.can_view, FALSE) AS can_view,
                COALESCE(udp.can_edit, FALSE) AS can_edit,
                COALESCE(udp.can_delete, FALSE) AS can_delete
            FROM v_dossiers_departement AS vdep
            LEFT JOIN user_document_permissions AS udp
                ON udp.document_id = vdep.document_id
               AND udp.user_id = ?
            WHERE vdep.dossier_id = ?
              AND vdep.departement_id = ?';

    return $this->db->query(
        $sql,
        [$userId, $dossierId, $departementId]
    )->getResultArray();
}

}