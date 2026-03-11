<?php

namespace App\Livewire\Admin;

use App\Models\Competition;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class CompetitionIndex extends Component
{
    use WithPagination;

    public function togglePublish(int $competitionId): void
    {
        $competition = Competition::findOrFail($competitionId);
        $competition->update(['is_published' => ! $competition->is_published]);
    }

    public function deleteCompetition(int $competitionId): void
    {
        Competition::findOrFail($competitionId)->delete();
    }

    public function render(): View
    {
        return view('livewire.admin.competition-index', [
            'competitions' => Competition::withCount('results')
                ->orderByDesc('date')
                ->paginate(15),
        ])->layout('layouts.app.sidebar');
    }
}
