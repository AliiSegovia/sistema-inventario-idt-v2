<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\User; // O App\Models\Operator si usas un modelo independiente
use Illuminate\Support\Facades\Hash;

#[Layout('layouts.app')]
class UserManager extends Component // (o OperatorManager según tu clase)
{
    use WithPagination;

    public $name, $email, $password, $userId;
    public $isOpen = false;
    public $editMode = false;
    public $search = '';

    public function render()
    {
        $users = User::query() // Cambiar a Operator si usas ese modelo
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(5);

        return view('livewire.user-manager', [
            'users' => $users
        ]);
    }

    public function create()
    {
        $this->reset(['name', 'email', 'password', 'userId', 'editMode']);
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = ''; // La contraseña se deja en blanco por seguridad al editar
        $this->editMode = true;
        $this->isOpen = true;
    }

    public function store()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
        ];

        // Si es nuevo o si escribieron una contraseña al editar, la pedimos
        if (!$this->editMode || !empty($this->password)) {
            $rules['password'] = 'required|min:6';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->userId], $data);

        session()->flash('message', $this->editMode ? 'Operario actualizado correctamente.' : 'Operario registrado correctamente.');
        
        $this->reset(['name', 'email', 'password', 'userId', 'isOpen', 'editMode']);
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(['name', 'email', 'password', 'userId', 'editMode']);
    }
}