<?php

namespace App\Controllers;

use App\Models\UtilisateurModel;
use App\Models\DocumentModel;
use App\Models\DossierModel;
use App\Models\DocumentPermissionModel;
use App\Models\DossierDepartementModel;
use App\Models\DocUserPermissionModel;
use App\Models\UserDocPermissionModel;


class Document extends BaseController
{
    public function list($dossierId)
    {
        $model = new DocUserPermissionModel();
        $userId = (int) session()->get('user_id');
        $departementId = session()->get('departement_id');
        return view('documents/list', [
        'documents' => $model->getDocumentsForUser($userId, $dossierId, $departementId),
        'dossierId' => $dossierId,
        'userId' => $userId,
        ]);
    }

   public function store()
    {
        $file = $this->request->getFile('fichier');
        $dossierId = $this->request->getPost('dossier_id'); 
        $validationRules = [
            'dossier_id' => 'required|is_natural_no_zero',
            'fichier' => 'uploaded[fichier]|max_size[fichier,10240]|ext_in[fichier,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,csv,jpg,jpeg,png]',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $departementId = session()->get('departement_id');
        $dossier = (new DossierModel())
            ->where('departement_id', $departementId)
            ->find($dossierId);

        if (!$dossier) {
            return redirect()->back()->with('errors', ['Le dossier sélectionné est invalide.']);
        }

        $uploadPath = WRITEPATH . 'uploads/documents';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0750, true);
        }

        $storedName = $file->getRandomName();
        $file->move($uploadPath, $storedName);
        $model = new DocumentModel();
        $documentId = $model->insert([
            'titre' => $file->getClientName(),
            'chemin_fichier' => 'uploads/documents/' . $storedName,
            'type_fichier' => strtoupper($file->getClientExtension()),
            'taille_fichier' => $file->getSize(),
            'dossier_id' => $dossierId,
            'created_by' => session()->get('user_id'),
        ]);

        if (!$documentId) {
            unlink($uploadPath . DIRECTORY_SEPARATOR . $storedName);
            return redirect()->back()->withInput()->with('errors', $model->errors());

        }
        $this->ajoutDocumentPermission($documentId);
        $this->ajoutDocumentUserPermission($documentId);
      
        return redirect()->to('/docinterne/documents/list/' . $dossierId);
    }

    public function edit($id)
    {
        $model = new DossierModel();
        $dossier = $model->find($id);
        if (!$dossier) {throw new \CodeIgniter\Exceptions\PageNotFoundException('Dossier non trouvé');}
        return view('dossiers/edit', ['dossier' => $dossier]);
    }

    public function update($id)
    {
        $model = new DossierModel();
        $dossier = $model->find($id);
        if (!$dossier) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Dossier non trouvé');}
        $data = ['nom' => $this->request->getPost('nom')];
        $model->update($id, $data);
        return redirect()->to('/docinterne/utilisateurs/accueiluser');
    }

    public function delete($id)
    {
        $model = new DossierModel();
        $dossier = $model->find($id);
        if (!$dossier) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Dossier non trouvé');}
        $model->delete($id);
        return redirect()->to('/docinterne/utilisateurs/accueiluser');
        
    }
    public function ajoutDocumentPermission($documentId)
    {
        $departementId = session()->get('departement_id');
        $permissionModel = new DocumentPermissionModel();
        $permissionModel->insert([
            'document_id' => $documentId,
            'departement_id' => $departementId,
            'can_view' => true,
            'can_edit' => true,
            'can_delete' => false,
        ]);
    }
    public function ajoutDocumentUserPermission($documentId)
    {
        $departementId = session()->get('departement_id');
        $userId = session()->get('user_id');
        $permissionModel = new UserDocPermissionModel();
        $permissionModel->insert([
            'document_id' => $documentId,
            'user_id' => $userId,
            'can_view' => true,
            'can_edit' => true,
            'can_delete' => true,
        ]);
    }
    public function pagePermission($documentId){
        $departementId = session()->get('departement_id');
        $document = (new DocumentModel())->find($documentId);
        $dossier = $document ? (new DossierModel())->where('id', $document['dossier_id'])->where('departement_id', $departementId)->first(): null;
        if (!$dossier) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Document non trouvé');
        }
        $model = new UtilisateurModel();
        $selectedUserId = filter_var($this->request->getGet('user_id'), FILTER_VALIDATE_INT);
        $selectedPermission = null;
        if ($selectedUserId) {
            $selectedUser = $model
                ->where('id', $selectedUserId)
                ->where('departement_id', $departementId)
                ->first();
            if ($selectedUser) {
                $selectedPermission = (new UserDocPermissionModel())->where('document_id', $documentId)->where('user_id', $selectedUserId)->first();
            } else {
                $selectedUserId = null;
            }
        }
        $data = [
            'utilisateurs' => $model->where('departement_id', $departementId)->findAll(),
            'documentId' => $documentId,
            'selectedUserId' => $selectedUserId,
            'selectedPermission' => $selectedPermission,
        ];
        return view('documents/pagePermission', $data);
    }

    public function savePermission($documentId)
    {
        $departementId = session()->get('departement_id');
        $document = (new DocumentModel())->find($documentId);
        $dossier = $document
            ? (new DossierModel())->where('id', $document['dossier_id'])->where('departement_id', $departementId)->first(): null;
        if (!$dossier) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Document non trouvé');
        }
        $userId = filter_var($this->request->getPost('user_id'), FILTER_VALIDATE_INT);
        $utilisateur = $userId ? (new UtilisateurModel())->where('id', $userId)->where('departement_id', $departementId)->first(): null;
        if (!$utilisateur) {
            return redirect()->back()->withInput()->with('errors', ['Veuillez choisir un utilisateur valide.']);
        }
        $permissionModel = new UserDocPermissionModel();
        $permission = $permissionModel->where('document_id', $documentId)->where('user_id', $userId)->first();
        $permissionData = [
            'can_view' => $this->request->getPost('can_view') === '1',
            'can_edit' => $this->request->getPost('can_edit') === '1',
            'can_delete' => $this->request->getPost('can_delete') === '1',
        ];

        if ($permission) {
            $saved = $permissionModel->update($permission['id'], $permissionData);
        }else {
            $saved = $permissionModel->insert($permissionData + [
                'document_id' => $documentId,
                'user_id' => $userId,
            ]);
        }
        if (!$saved) {
            $errors = $permissionModel->errors() ?: ['Impossible d’enregistrer les permissions.'];
            return redirect()->back()->withInput()->with('errors', $errors);
        }
        return redirect()->to('/docinterne/documents/pagePermission/' . $documentId . '?user_id=' . $userId)->with('success', 'Les permissions ont été enregistrées.');
    }


}