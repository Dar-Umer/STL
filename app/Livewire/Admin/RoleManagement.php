<?php

namespace App\Livewire\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleManagement extends Component
{
    use AuthorizesRequests;

    public $roleId;
    public $name;
    public $permissions = [];
    public $showForm = false;

    protected function rules()
    {
        return [
            'name' => 'required|unique:roles,name,' . $this->roleId,
        ];
    }

    public function render()
    {
        return view('livewire.admin.role-management', [
            'roles' => Role::with('permissions')->get(),
            'allPermissions' => Permission::all(),
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->reset();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->permissions = $role->permissions->pluck('name')->toArray();
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();
        if ($this->roleId) {
            $role = Role::findOrFail($this->roleId);
            $role->update(['name' => $this->name]);
            $role->syncPermissions($this->permissions);
            session()->flash('success', 'Role updated.');
        } else {
            $role = Role::create(['name' => $this->name]);
            $role->syncPermissions($this->permissions);
            session()->flash('success', 'Role created.');
        }
        $this->reset();
    }

    public function cancel()
    {
        $this->reset();
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'Admin') {
            session()->flash('error', 'The Admin role cannot be deleted.');
            return;
        }

        if ($role->users()->count() > 0) {
            session()->flash('error', 'Cannot delete role "' . $role->name . '" — ' . $role->users()->count() . ' user(s) are assigned to it. Reassign them first.');
            return;
        }

        $role->syncPermissions([]);
        $role->delete();

        session()->flash('success', 'Role "' . $role->name . '" deleted.');
    }
}
