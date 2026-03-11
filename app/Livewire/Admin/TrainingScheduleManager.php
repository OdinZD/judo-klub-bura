<?php

namespace App\Livewire\Admin;

use App\Enums\DayOfWeek;
use App\Models\TrainingGroup;
use App\Models\TrainingSession;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class TrainingScheduleManager extends Component
{
    public string $newGroupName = '';

    public string $newGroupAgeRange = '';

    public string $newGroupIcon = 'heart';

    public string $newGroupIconColor = 'text-bura-500';

    // Edit group fields
    public ?int $editingGroupId = null;

    public string $editGroupName = '';

    public string $editGroupAgeRange = '';

    public string $editGroupIcon = '';

    public string $editGroupIconColor = '';

    // New session fields
    public ?int $addingSessionGroupId = null;

    public int $newSessionDay = 1;

    public string $newSessionStart = '';

    public string $newSessionEnd = '';

    public function addGroup(): void
    {
        $this->validate([
            'newGroupName' => ['required', 'string', 'max:255'],
            'newGroupAgeRange' => ['required', 'string', 'max:255'],
            'newGroupIcon' => ['required', 'string', 'max:50'],
            'newGroupIconColor' => ['required', 'string', 'max:50'],
        ]);

        $maxSort = TrainingGroup::max('sort_order') ?? -1;

        TrainingGroup::create([
            'name' => $this->newGroupName,
            'age_range' => $this->newGroupAgeRange,
            'icon' => $this->newGroupIcon,
            'icon_color' => $this->newGroupIconColor,
            'sort_order' => $maxSort + 1,
        ]);

        $this->reset(['newGroupName', 'newGroupAgeRange', 'newGroupIcon', 'newGroupIconColor']);
    }

    public function startEditGroup(int $groupId): void
    {
        $group = TrainingGroup::findOrFail($groupId);
        $this->editingGroupId = $group->id;
        $this->editGroupName = $group->name;
        $this->editGroupAgeRange = $group->age_range;
        $this->editGroupIcon = $group->icon;
        $this->editGroupIconColor = $group->icon_color;
    }

    public function saveGroup(): void
    {
        $this->validate([
            'editGroupName' => ['required', 'string', 'max:255'],
            'editGroupAgeRange' => ['required', 'string', 'max:255'],
            'editGroupIcon' => ['required', 'string', 'max:50'],
            'editGroupIconColor' => ['required', 'string', 'max:50'],
        ]);

        $group = TrainingGroup::findOrFail($this->editingGroupId);
        $group->update([
            'name' => $this->editGroupName,
            'age_range' => $this->editGroupAgeRange,
            'icon' => $this->editGroupIcon,
            'icon_color' => $this->editGroupIconColor,
        ]);

        $this->editingGroupId = null;
    }

    public function cancelEditGroup(): void
    {
        $this->editingGroupId = null;
    }

    public function toggleGroupActive(int $groupId): void
    {
        $group = TrainingGroup::findOrFail($groupId);
        $group->update(['is_active' => ! $group->is_active]);
    }

    public function moveGroup(int $groupId, string $direction): void
    {
        $group = TrainingGroup::findOrFail($groupId);
        $groups = TrainingGroup::orderBy('sort_order')->get();

        $index = $groups->search(fn ($g) => $g->id === $groupId);

        if ($direction === 'up' && $index > 0) {
            $swap = $groups[$index - 1];
            $tmpSort = $group->sort_order;
            $group->update(['sort_order' => $swap->sort_order]);
            $swap->update(['sort_order' => $tmpSort]);
        } elseif ($direction === 'down' && $index < $groups->count() - 1) {
            $swap = $groups[$index + 1];
            $tmpSort = $group->sort_order;
            $group->update(['sort_order' => $swap->sort_order]);
            $swap->update(['sort_order' => $tmpSort]);
        }
    }

    public function deleteGroup(int $groupId): void
    {
        TrainingGroup::findOrFail($groupId)->delete();
    }

    public function showAddSession(int $groupId): void
    {
        $this->addingSessionGroupId = $groupId;
        $this->reset(['newSessionDay', 'newSessionStart', 'newSessionEnd']);
        $this->newSessionDay = 1;
    }

    public function addSession(): void
    {
        $this->validate([
            'newSessionDay' => ['required', 'integer', 'between:1,7'],
            'newSessionStart' => ['required', 'string', 'regex:/^\d{2}:\d{2}$/'],
            'newSessionEnd' => ['required', 'string', 'regex:/^\d{2}:\d{2}$/'],
        ]);

        TrainingSession::create([
            'training_group_id' => $this->addingSessionGroupId,
            'day_of_week' => $this->newSessionDay,
            'start_time' => $this->newSessionStart,
            'end_time' => $this->newSessionEnd,
        ]);

        $this->addingSessionGroupId = null;
    }

    public function cancelAddSession(): void
    {
        $this->addingSessionGroupId = null;
    }

    public function removeSession(int $sessionId): void
    {
        TrainingSession::findOrFail($sessionId)->delete();
    }

    public function render(): View
    {
        return view('livewire.admin.training-schedule-manager', [
            'groups' => TrainingGroup::orderBy('sort_order')->with('sessions')->get(),
            'days' => DayOfWeek::cases(),
        ])->layout('layouts.app.sidebar');
    }
}
