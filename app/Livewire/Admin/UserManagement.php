<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Lab;
use App\Models\Location;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserManagement extends Component
{
    use AuthorizesRequests, WithPagination;

    public $showForm = false;
    public $userId;
    public $name, $email, $password, $phone, $is_active = true, $lab_id, $zone_id, $role;
    public $search = '';

    protected function rules()
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'phone' => 'nullable',
            'is_active' => 'boolean',
            'lab_id' => 'nullable|exists:labs,id',
            'zone_id' => 'nullable|exists:locations,id',
            'role' => 'required|exists:roles,name',
        ];
        if (!$this->userId) {
            $rules['password'] = 'required|min:6';
        } elseif ($this->password) {
            $rules['password'] = 'min:6';
        }
        return $rules;
    }

    public function render()
    {
        $user = auth()->user();
        $isLabManager = $user->hasRole('Lab Manager');

        $users = User::with('roles', 'lab')
            ->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            });

        if ($isLabManager) {
            $users->where('lab_id', $user->lab_id);
        }

        $users = $users->orderBy('created_at', 'desc')->paginate(10);

        $availableRoles = $isLabManager
            ? Role::where('name', '!=', 'Admin')->get()
            : Role::all();

        return view('livewire.admin.user-management', [
            'users' => $users,
            'labs' => $isLabManager ? collect() : Lab::where('is_active', true)->get(),
            'zones' => Location::where('type', 'zone')->orderBy('name')->get(),
            'roles' => $availableRoles,
            'isLabManager' => $isLabManager,
        ])->layout('layouts.app');
    }

    public function create()
    {
        $user = auth()->user();
        $this->reset();
        if ($user->hasRole('Lab Manager')) {
            $this->lab_id = $user->lab_id;
        }
        $this->showForm = true;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $currentUser = auth()->user();

        if ($currentUser->hasRole('Lab Manager') && $user->lab_id !== $currentUser->lab_id) {
            abort(403);
        }

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->is_active = $user->is_active;
        $this->lab_id = $user->lab_id;
        $this->zone_id = $user->zone_id;
        $this->role = $user->roles->first()?->name;
        $this->showForm = true;
    }

    public function save()
    {
        $currentUser = auth()->user();
        $isLabManager = $currentUser->hasRole('Lab Manager');

        if ($isLabManager) {
            $this->lab_id = $currentUser->lab_id;
        }

        $this->lab_id = $this->lab_id ?: null;
        $this->validate();
        if ($this->userId) {
            $user = User::findOrFail($this->userId);

            if ($isLabManager && $user->lab_id !== $currentUser->lab_id) {
                abort(403);
            }

            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'is_active' => $this->is_active,
                'lab_id' => $this->lab_id,
                'zone_id' => $this->role === 'Field Official' ? $this->zone_id : null,
            ];
            if ($this->password) {
                $data['password'] = bcrypt($this->password);
            }
            $user->update($data);
            $user->syncRoles([$this->role]);
            session()->flash('success', 'User updated successfully.');
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'phone' => $this->phone,
                'is_active' => $this->is_active,
                'lab_id' => $this->lab_id,
                'zone_id' => $this->role === 'Field Official' ? $this->zone_id : null,
            ]);
            $user->assignRole($this->role);
            session()->flash('success', 'User created successfully.');
        }
        $this->reset();
    }

    public function toggleActive($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
    }

    public function cancel()
    {
        $this->reset();
    }
}
