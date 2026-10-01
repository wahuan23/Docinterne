<?php

namespace App\Controllers;

use App\Models\DepartementModel;

class Departement extends BaseController
{
    public function index()
    {
        $model = new DepartementModel();
        $data = ['departements' => $model->findAll()];
        return view('departements/index', $data);
    }

    public function create()
    {
        return view('departements/create');
    }

   public function store()
    {
        $model = new DepartementModel();
        $data = [
            'nom' => $this->request->getPost('nom')
        ];
        if (!$model->insert($data)) {
         dd($model->errors(), $model->db->error());
        }
        return redirect()->to('/docinterne/departements');
    }


    public function edit($id)
    {
        $model = new DepartementModel();
        $departement = $model->find($id);
        if (!$departement) {throw new \CodeIgniter\Exceptions\PageNotFoundException('Département non trouvé');}
        return view('departements/edit', ['departement' => $departement]);

    }

    public function update($id)
{
        $model = new DepartementModel();
        $departement = $model->find($id);
        if (!$departement) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Département non trouvé');}
        $data = ['nom' => $this->request->getPost('nom')];
        $model->update($id, $data);
        return redirect()->to('/docinterne/departements');
}

    public function delete($id)
    {
        $model = new DepartementModel();
        $departement = $model->find($id);
        if (!$departement) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Département non trouvé');}
        $model->delete($id);
        return redirect()->to('/docinterne/departements');
        
    }



}