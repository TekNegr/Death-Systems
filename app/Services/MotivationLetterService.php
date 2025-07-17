<?php

namespace App\Services;

class MotivationLetterService
{
    protected array $templates = [
        'new_application' => "Dear {company_name},\n\nI am writing to express my interest in joining your company. With my skills in {skills}, my experience in {experiences}, and my education in {formations}, I believe I can contribute significantly to your team.\n\nSincerely,\n{applicant_name}",
        'follow_up' => "Dear {company_name},\n\nFollowing up on my previous application, I would like to reiterate my enthusiasm for the opportunity. My skills in {skills}, experience in {experiences}, and education in {formations} make me a strong candidate.\n\nBest regards,\n{applicant_name}",
    ];

    /**
     * Generate a motivation letter based on the template type and data.
     *
     * @param string $templateType 'new_application' or 'follow_up'
     * @param array $data Associative array with keys:
     *                    - company_name
     *                    - skills (string)
     *                    - experiences (string)
     *                    - formations (string)
     *                    - applicant_name
     * @return string
     */
    public function generateLetter(string $templateType, array $data): string
    {
        $template = $this->templates[$templateType] ?? $this->templates['new_application'];

        foreach ($data as $key => $value) {
            $template = str_replace("{" . $key . "}", $value, $template);
        }

        return $template;
    }
}
