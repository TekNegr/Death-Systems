<?php

namespace App\Services;

use App\Models\Skills;
use App\Models\Formation;
use App\Models\Experience;

class SelectorService
{
    /**
     * Select the best matching skills, formations, and experiences based on target data.
     *
     * @param array $targetData Associative array with keys like 'company_name', 'work_domain', 'job_description'
     * @return array Associative array with keys 'skills', 'formations', 'experiences' containing selected items.
     */
    public function selectBestMatches(array $targetData): array
    {
        // For now, this is a placeholder implementation.
        // You can replace this with AI or advanced matching logic.

        // Fetch all user data
        $skills = Skills::all();
        $formations = Formation::all();
        $experiences = Experience::all();

        // Simple heuristic: return top 3 of each as example
        return [
            'skills' => $skills->take(3)->pluck('name')->toArray(),
            'formations' => $formations->take(3)->map(function ($f) {
                return $f->school . ' - ' . $f->degree;
            })->toArray(),
            'experiences' => $experiences->take(3)->map(function ($e) {
                return $e->company . ' - ' . $e->position;
            })->toArray(),
        ];
    }
}
