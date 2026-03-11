<?php

namespace App\Livewire\Admin;

use App\Enums\PlacementType;
use App\Models\Competition;
use App\Models\CompetitionResult;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CompetitionForm extends Component
{
    public ?Competition $competition = null;

    public string $name = '';

    public string $date = '';

    public string $location = '';

    public string $description = '';

    public bool $is_published = false;

    // Results management
    public array $results = [];

    public string $newAthleteName = '';

    public string $newWeightCategory = '';

    public string $newPlacement = 'gold';

    public function mount(?Competition $competition = null): void
    {
        if ($competition?->exists) {
            $this->competition = $competition;
            $this->name = $competition->name;
            $this->date = $competition->date->format('Y-m-d');
            $this->location = $competition->location ?? '';
            $this->description = $competition->description ?? '';
            $this->is_published = $competition->is_published;

            $this->results = $competition->results->map(fn ($r) => [
                'id' => $r->id,
                'athlete_name' => $r->athlete_name,
                'weight_category' => $r->weight_category ?? '',
                'placement' => $r->placement->value,
            ])->toArray();
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'is_published' => ['boolean'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'date' => $this->date,
            'location' => $this->location ?: null,
            'description' => $this->description ?: null,
            'is_published' => $this->is_published,
        ];

        if ($this->competition?->exists) {
            $this->competition->update($data);
            $competition = $this->competition;
        } else {
            $competition = Competition::create($data);
        }

        // Sync results
        $existingIds = collect($this->results)->pluck('id')->filter()->toArray();

        // Delete removed results
        $competition->results()->whereNotIn('id', $existingIds)->delete();

        // Update or create results
        foreach ($this->results as $result) {
            if (! empty($result['id'])) {
                CompetitionResult::where('id', $result['id'])->update([
                    'athlete_name' => $result['athlete_name'],
                    'weight_category' => $result['weight_category'] ?: null,
                    'placement' => $result['placement'],
                ]);
            } else {
                $competition->results()->create([
                    'athlete_name' => $result['athlete_name'],
                    'weight_category' => $result['weight_category'] ?: null,
                    'placement' => $result['placement'],
                ]);
            }
        }

        session()->flash('message', $this->competition?->exists ? 'Natjecanje je ažurirano.' : 'Natjecanje je kreirano.');

        $this->redirectRoute('admin.competitions.edit', ['competition' => $competition], navigate: true);
    }

    public function addResult(): void
    {
        $this->validate([
            'newAthleteName' => ['required', 'string', 'max:255'],
            'newWeightCategory' => ['nullable', 'string', 'max:100'],
            'newPlacement' => ['required', 'string', 'in:'.implode(',', array_column(PlacementType::cases(), 'value'))],
        ]);

        $this->results[] = [
            'id' => null,
            'athlete_name' => $this->newAthleteName,
            'weight_category' => $this->newWeightCategory,
            'placement' => $this->newPlacement,
        ];

        $this->reset(['newAthleteName', 'newWeightCategory']);
        $this->newPlacement = 'gold';
    }

    public function removeResult(int $index): void
    {
        unset($this->results[$index]);
        $this->results = array_values($this->results);
    }

    public function render(): View
    {
        return view('livewire.admin.competition-form', [
            'isEditing' => $this->competition?->exists ?? false,
            'placements' => PlacementType::cases(),
        ])->layout('layouts.app.sidebar');
    }
}
