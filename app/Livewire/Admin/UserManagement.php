<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Lab;
use App\Models\Location;
use App\Models\SoilSample;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
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
        $currentUser = auth()->user();
        $isLabManager = $currentUser && $currentUser->hasRole('Lab Manager');

        $rules = [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->userId,
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'lab_id' => 'nullable|exists:labs,id',
            'zone_id' => 'nullable|exists:locations,id',
            'role' => $isLabManager
                ? ['required', 'string', Rule::in(['Lab Technician', 'Field Official'])]
                : ['required', 'string', 'exists:roles,name'],
        ];

        if (!$this->userId) {
            $rules['password'] = 'required|string|min:6';
        } elseif ($this->password) {
            $rules['password'] = 'min:6';
        }

        return $rules;
    }

    public function render()
    {
        $currentUser = auth()->user();
        $isLabManager = $currentUser->hasRole('Lab Manager');

        $query = User::with('roles', 'lab', 'zone')
            ->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('phone', 'like', "%{$this->search}%");
            });

        if ($isLabManager) {
            // Lab Manager can ONLY view users with roles strictly below them in their assigned lab
            $query->where('lab_id', $currentUser->lab_id)
                  ->whereHas('roles', function ($q) {
                      $q->whereIn('name', ['Lab Technician', 'Field Official']);
                  });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        // Subordinate roles only for Lab Manager
        $availableRoles = $isLabManager
            ? Role::whereIn('name', ['Lab Technician', 'Field Official'])->get()
            : Role::where('name', '!=', 'Farmer')->get();

        return view('livewire.admin.user-management', [
            'users' => $users,
            'labs' => $isLabManager ? collect() : Lab::where('is_active', true)->get(),
            'zones' => Location::where('type', 'zone')->orderBy('name')->get(),
            'roles' => $availableRoles,
            'isLabManager' => $isLabManager,
            'managerLab' => $isLabManager ? Lab::find($currentUser->lab_id) : null,
        ])->layout('layouts.app');
    }

    public function create()
    {
        $currentUser = auth()->user();
        $this->reset(['userId', 'name', 'email', 'password', 'phone', 'is_active', 'lab_id', 'zone_id', 'role']);
        $this->is_active = true;

        if ($currentUser->hasRole('Lab Manager')) {
            $this->lab_id = $currentUser->lab_id;
            $this->role = 'Lab Technician'; // Default to a subordinate role
        }

        $this->showForm = true;
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $currentUser = auth()->user();

        if ($currentUser->hasRole('Lab Manager')) {
            // Lab Manager can ONLY edit subordinate roles in their own lab
            if ($user->lab_id !== $currentUser->lab_id || !$user->hasAnyRole(['Lab Technician', 'Field Official'])) {
                session()->flash('error', 'Unauthorized: You can only edit Lab Technicians and Field Officials assigned to your laboratory.');
                return;
            }
        }

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->is_active = (bool) $user->is_active;
        $this->lab_id = $user->lab_id;
        $this->zone_id = $user->zone_id;
        $this->role = $user->roles->first()?->name;
        $this->password = null;
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
        $this->zone_id = $this->zone_id ?: null;

        $this->validate();

        if ($isLabManager && !in_array($this->role, ['Lab Technician', 'Field Official'])) {
            session()->flash('error', 'Unauthorized: You can only assign roles below you (Lab Technician or Field Official).');
            return;
        }

        if ($this->userId) {
            $user = User::with('roles')->findOrFail($this->userId);

            if ($isLabManager) {
                // Strict authorization check: cannot modify Admin, Lab Manager, or other lab's users
                if ($user->lab_id !== $currentUser->lab_id || !$user->hasAnyRole(['Lab Technician', 'Field Official'])) {
                    session()->flash('error', 'Unauthorized: You can only modify Lab Technicians and Field Officials assigned to your laboratory.');
                    return;
                }
            }

            $data = [
                'name' => trim($this->name),
                'email' => strtolower(trim($this->email)),
                'phone' => $this->phone ? trim($this->phone) : null,
                'is_active' => (bool) $this->is_active,
                'lab_id' => $isLabManager ? $currentUser->lab_id : $this->lab_id,
                'zone_id' => $this->role === 'Field Official' ? ($this->zone_id ?: null) : null,
            ];

            if (!empty($this->password)) {
                $data['password'] = $this->password;
            }

            $user->update($data);
            $user->syncRoles([$this->role]);
            session()->flash('success', "User '{$user->name}' updated successfully.");
        } else {
            $user = User::create([
                'name' => trim($this->name),
                'email' => strtolower(trim($this->email)),
                'password' => $this->password,
                'phone' => $this->phone ? trim($this->phone) : null,
                'is_active' => (bool) $this->is_active,
                'lab_id' => $isLabManager ? $currentUser->lab_id : $this->lab_id,
                'zone_id' => $this->role === 'Field Official' ? ($this->zone_id ?: null) : null,
            ]);
            $user->assignRole($this->role);
            session()->flash('success', "User '{$user->name}' created successfully.");
        }

        $this->reset(['userId', 'name', 'email', 'password', 'phone', 'is_active', 'lab_id', 'zone_id', 'role', 'showForm']);
    }

    public function toggleActive($id)
    {
        $currentUser = auth()->user();
        $targetUser = User::with('roles')->findOrFail($id);

        // Security check: cannot suspend self
        if ($currentUser->id === $targetUser->id) {
            session()->flash('error', 'You cannot suspend or activate your own account.');
            return;
        }

        // Security check for Lab Manager: can ONLY suspend/activate roles below them in their lab
        if ($currentUser->hasRole('Lab Manager')) {
            if ($targetUser->lab_id !== $currentUser->lab_id || !$targetUser->hasAnyRole(['Lab Technician', 'Field Official'])) {
                session()->flash('error', 'Unauthorized: You can only suspend or activate Lab Technicians and Field Officials assigned to your laboratory.');
                return;
            }
        }

        // Security check: non-Admin cannot suspend Admin
        if ($targetUser->hasRole('Admin') && !$currentUser->hasRole('Admin')) {
            session()->flash('error', 'Unauthorized: You cannot suspend an Administrator.');
            return;
        }

        $targetUser->update(['is_active' => !$targetUser->is_active]);
        $statusStr = $targetUser->is_active ? 'activated' : 'suspended';
        session()->flash('success', "User '{$targetUser->name}' has been {$statusStr}.");
    }

    public function delete($id)
    {
        $currentUser = auth()->user();
        $targetUser = User::with('roles')->findOrFail($id);

        // Security check: cannot delete self
        if ($currentUser->id === $targetUser->id) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }

        // Security check for Lab Manager: can ONLY delete roles below them in their lab
        if ($currentUser->hasRole('Lab Manager')) {
            if ($targetUser->lab_id !== $currentUser->lab_id || !$targetUser->hasAnyRole(['Lab Technician', 'Field Official'])) {
                session()->flash('error', 'Unauthorized: You can only delete Lab Technicians and Field Officials assigned to your laboratory.');
                return;
            }
        }

        // Security check for Admin: cannot delete the last Admin
        if ($targetUser->hasRole('Admin')) {
            $adminCount = User::role('Admin')->count();
            if ($adminCount <= 1) {
                session()->flash('error', 'Cannot delete the only remaining Administrator account.');
                return;
            }
        }

        // Foreign key integrity check: check if user is referenced in soil samples
        $hasSampleActivity = SoilSample::where('collected_by', $targetUser->id)
            ->orWhere('analyzed_by', $targetUser->id)
            ->exists();

        if ($hasSampleActivity) {
            session()->flash('error', "Cannot delete '{$targetUser->name}' because they have recorded soil sample activity. You can suspend the account instead to disable access.");
            return;
        }

        $userName = $targetUser->name;
        $targetUser->roles()->detach();
        $targetUser->delete();

        session()->flash('success', "User '{$userName}' has been permanently deleted.");
    }

    public function cancel()
    {
        $this->reset(['userId', 'name', 'email', 'password', 'phone', 'is_active', 'lab_id', 'zone_id', 'role', 'showForm']);
    }
}
