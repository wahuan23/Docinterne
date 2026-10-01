<?php
namespace App\Controllers;
use App\Models\UtilisateurModel;

class Auth extends BaseController
{
    public function login()
    {
        $model = new UtilisateurModel();
        return view('auth/login');
    }

    public function authenticate()
    {
        $model = new UtilisateurModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $user = $model->where('email', $email)->first();
        if ($user ) {
            session()->regenerate(true);
            session()->set([
            'user_id'    => $user['id'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'departement_id' => $user['departement_id'],
            'isLoggedIn' => true,
        ]);

        if ($user['role'] === 'admin') {
            return redirect()->to('/docinterne/admin');
        }
        else if ($user['role'] === 'user') {
            return redirect()->to('/docinterne/utilisateur');
        }
           
        } else {
            return redirect()->back()->withInput()->with('error', 'Email ou mot de passe incorrect.');
        }
    }
    
    public function logout()
    {
        session()->destroy();

        return redirect()->to('/docinterne');
    }




}