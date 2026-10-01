<?php

namespace App\Controllers;

use App\Models\DossierModel;
class Dossier extends BaseController
{
    
   public function store()
    {
        $model = new DossierModel();
        $departementId = session()->get('departement_id');
        $parentId = $model->where('departement_id', $departementId)->where('parent_id', NULL)->first();
        $data = [
            'nom' => $this->request->getPost('dossier_nom'),
            'departement_id' => $departementId,
            'parent_id' => $parentId ? $parentId['id'] : NULL,
        ];
        if (!$model->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('/docinterne/utilisateur');
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



}