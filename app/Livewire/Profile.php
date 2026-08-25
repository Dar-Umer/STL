<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Profile extends Component
{
    public string $name;
    public string $email;
    public string $currentPassword = '';
    public string $newPassword = '';
    public string $newPasswordConfirmation = '';

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function save()
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
        ];

        if ($this->email !== $user->email || $this->newPassword) {
            $rules['currentPassword'] = 'required|string';
        }

        if ($this->newPassword) {
            $rules['newPassword'] = 'required|string|min:8|same:newPasswordConfirmation';
        }

        $this->validate($rules);

        if ($this->email !== $user->email || $this->newPassword) {
            if (!Hash::check($this->currentPassword, $user->password)) {
                $this->addError('currentPassword', 'Current password is incorrect.');
                return;
            }
        }

        if ($this->newPassword) {
            $user->password = Hash::make($this->newPassword);
        }

        $user->name = $this->name;
        $user->email = $this->email;
        $user->save();

        session()->flash('success', 'Profile updated successfully.');
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
