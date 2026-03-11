<?php

namespace App\Livewire\Admin;

use App\Models\Coach;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class CoachIndex extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $role = '';

    public string $belt = '';

    public string $bio = '';

    public $photo;

    public bool $is_active = true;

    public ?int $editingId = null;

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'belt' => ['nullable', 'string', 'max:50'],
            'bio' => ['nullable', 'string', 'max:5000'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['boolean'],
        ]);

        $data = [
            'name' => $this->name,
            'role' => $this->role,
            'belt' => $this->belt ?: null,
            'bio' => $this->bio ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->photo) {
            $data['photo_path'] = $this->photo->store('coaches', 'public');
        }

        if ($this->editingId) {
            Coach::findOrFail($this->editingId)->update($data);
        } else {
            $data['sort_order'] = (Coach::max('sort_order') ?? -1) + 1;
            Coach::create($data);
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $coach = Coach::findOrFail($id);
        $this->editingId = $coach->id;
        $this->name = $coach->name;
        $this->role = $coach->role;
        $this->belt = $coach->belt ?? '';
        $this->bio = $coach->bio ?? '';
        $this->is_active = $coach->is_active;
        $this->photo = null;
    }

    public function cancelEdit(): void
    {
        $this->resetForm();
    }

    public function deleteCoach(int $id): void
    {
        Coach::findOrFail($id)->delete();
    }

    public function moveCoach(int $id, string $direction): void
    {
        $coach = Coach::findOrFail($id);
        $coaches = Coach::orderBy('sort_order')->get();

        $index = $coaches->search(fn ($c) => $c->id === $id);

        if ($direction === 'up' && $index > 0) {
            $swap = $coaches[$index - 1];
            $tmpSort = $coach->sort_order;
            $coach->update(['sort_order' => $swap->sort_order]);
            $swap->update(['sort_order' => $tmpSort]);
        } elseif ($direction === 'down' && $index < $coaches->count() - 1) {
            $swap = $coaches[$index + 1];
            $tmpSort = $coach->sort_order;
            $coach->update(['sort_order' => $swap->sort_order]);
            $swap->update(['sort_order' => $tmpSort]);
        }
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->reset(['name', 'role', 'belt', 'bio', 'photo', 'is_active']);
        $this->is_active = true;
    }

    public function render(): View
    {
        return view('livewire.admin.coach-index', [
            'coaches' => Coach::orderBy('sort_order')->get(),
        ])->layout('layouts.app.sidebar');
    }
}
