<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::orderBy('name');
        if (($term = trim((string) $request->input('q'))) !== '') {
            $like = '%'.addcslashes($term, '%_\\').'%';
            $query->where(fn ($w) => $w->where('name', 'like', $like)->orWhere('email', 'like', $like));
        }

        return view('admin.users.index', ['users' => $query->paginate(15)->withQueryString()]);
    }

    public function create()
    {
        return view('admin.users.form', ['user' => new User(['role' => User::ROLE_EDITOR, 'is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'role' => ['required', Rule::in([User::ROLE_SUPER_ADMIN, User::ROLE_EDITOR])],
            'password' => ['required', Password::min(10)->letters()->mixedCase()->numbers()],
            'is_active' => ['nullable', 'boolean'],
        ]);

        User::create($data + ['is_active' => $request->boolean('is_active'), 'must_change_password' => true]);

        return redirect()->route('admin.users.index')->with('success', 'User created. They must change the password at first login.');
    }

    public function edit(User $user)
    {
        return view('admin.users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in([User::ROLE_SUPER_ADMIN, User::ROLE_EDITOR])],
            'password' => ['nullable', Password::min(10)->letters()->mixedCase()->numbers()],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($this->wouldRemoveLastSuperAdmin($user, $data['role'], $data['is_active'])) {
            return back()->withInput()->withErrors(['role' => 'At least one active super administrator is required.']);
        }

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['must_change_password'] = true;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        if ($this->wouldRemoveLastSuperAdmin($user, 'editor', false)) {
            return back()->with('error', 'At least one active super administrator is required.');
        }

        $user->delete();

        return back()->with('success', 'User deleted.');
    }

    private function wouldRemoveLastSuperAdmin(User $user, string $newRole, bool $newActive): bool
    {
        if (! $user->isSuperAdmin() || ($newRole === User::ROLE_SUPER_ADMIN && $newActive)) {
            return false;
        }

        return User::where('role', User::ROLE_SUPER_ADMIN)->where('is_active', true)->whereKeyNot($user->id)->doesntExist();
    }
}
