<?php

namespace App\Services;

use App\Models\Entreprise;
use App\Models\JobOffer;
use App\Models\Skills;
use App\Models\Formation;
use App\Models\Experience;

class EmbeddingService
{
    /**
     * Get embedding for a selected Entreprise or JobOffer.
     *
     * @param Entreprise|JobOffer $entity
     * @return array
     */
    public function getEntityEmbedding($entity): array
    {
        // Concatenate relevant text fields
        $text = '';

        if ($entity instanceof Entreprise) {
            $text = 'Spontaneous application ' . $entity->company_name . ' ' . $entity->work_domain;
        } elseif ($entity instanceof JobOffer) {
            $text = $entity->title . ' ' . $entity->description;
        }

        // Return text as string for embedding, not numeric array
        return ['text' => $text];
    }

    /**
     * Get embeddings for all relevant resources (Skills, Formation, Experience).
     *
     * @return array
     */
    public function getResourceEmbeddings(): array
    {
        $skills = Skills::all()->map(function ($skill) {
            return $skill->name . ' ' . ($skill->description ?? '');
        })->toArray();

        $formations = Formation::all()->map(function ($formation) {
            return $formation->school . ' ' . ($formation->degree ?? '') . ' ' . ($formation->field_of_study ?? '');
        })->toArray();

        $experiences = Experience::all()->map(function ($experience) {
            return $experience->company . ' ' . $experience->position;
        })->toArray();

        return [
            'skills' => $skills,
            'formations' => $formations,
            'experiences' => $experiences,
        ];
    }
}
