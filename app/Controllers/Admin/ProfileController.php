<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class ProfileController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $id = session()->get('id');
        $data['user'] = $this->userModel->find($id);
        return view('admin/profile/index', $data);
    }

    public function update()
    {
        $id = session()->get('id');
        
        $rules = [
            'username' => "required|is_unique[user.username,id,$id]",
        ];

        if ($this->request->getVar('password')) {
            $rules['password'] = 'min_length[6]';
            $rules['confirm_password'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getVar('username'),
        ];

        if ($this->request->getVar('password')) {
            $data['password'] = password_hash($this->request->getVar('password'), PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);

        // Update session if username changed
        session()->set('username', $data['username']);

        return redirect()->to('/admin/profile')->with('success', 'Profil berhasil diupdate');
    }
}
