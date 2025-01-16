<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function updateAvatar()
    {
        $validator = $this->validate($this->request->files, [
            'avatar' => 'required|image|max:5120' // 5MB
        ]);

        if ($validator->fails()) {
            return $this->redirect()->back()->withErrors($validator);
        }

        $user = User::find($this->request->user()->id);
        
        // Delete old avatar if exists
        if ($user->avatar) {
            $user->deleteFile('avatar');
        }

        // Upload new avatar
        if ($file = $user->uploadFile('avatar', $this->request->file('avatar'))) {
            $user->addFile('avatar', $file);
            $user->save();
        }

        return $this->redirect()->back()->with('success', 'Avatar updated successfully');
    }
} 