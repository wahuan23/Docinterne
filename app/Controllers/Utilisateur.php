<?php

namespace App\Controllers;

use App\Models\DepartementModel;
use App\Models\UtilisateurModel;
use App\Models\DossierDepartementModel;

class Utilisateur extends BaseController
{
    public function Accueiluser()
    {
        $model = new DossierDepartementModel();
        $departementId = session()->get('departement_id');
        $data = [
        'dossiers' => $model
            ->where('departement_id', $departementId)
            ->findAll()
        ];
        return view('utilisateurs/accueiluser', $data);
    }

    public function create()
    {
        $model = new DepartementModel();
        $data = ['departements' => $model->findAll()];
        return view('utilisateurs/create', $data);
    }

   public function store()
    {
        $model = new UtilisateurModel();
        $matricule = 'MAT-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(4)));
        $data = [
            'matricule' => $matricule,
            'nom'        => $this->request->getPost('nom'),
            'email'      => $this->request->getPost('email'),
            'password' => password_hash(
                $this->request->getPost('password'),PASSWORD_DEFAULT),
            'departement_id'      => $this->request->getPost('departement_id'),
            'role' => $this->request->getPost('role'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        if (!$model->insert($data)) {
         dd($model->errors(), $model->db->error());
        }
        return redirect()->to('/docinterne/utilisateurs');
    }


    public function edit($id)
    {
        $model = new UtilisateurModel();
        $utilisateur = $model->find($id);
        if (!$utilisateur) {throw new \CodeIgniter\Exceptions\PageNotFoundException('Utilisateur non trouvé');}
        return view('utilisateurs/edit', ['utilisateur' => $utilisateur]);

    }

    public function update($id)
{
        $model = new UtilisateurModel();
        $utilisateur = $model->find($id);
        if (!$utilisateur) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Utilisateur non trouvé');}
        $data = ['nom' => $this->request->getPost('nom')];
        $model->update($id, $data);
        return redirect()->to('/docinterne/utilisateurs');
}

    public function delete($id)
    {
        $model = new UtilisateurModel();
        $utilisateur = $model->find($id);
        if (!$utilisateur) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Utilisateur non trouvé');}
        $model->delete($id);
        return redirect()->to('/docinterne/utilisateurs');
        
    }



}