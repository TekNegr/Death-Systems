<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TrainingDialog;
use App\Models\Entreprise;
use App\Models\JobOffer;
use App\Models\Skills;
use App\Models\Experience;
use App\Models\Formation;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        // Create admin user if not exists
        if (!User::where('email', 'admin@mail.com')->exists()) {
            $admin = User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'password' => bcrypt('admin1234'),
            ]);
            $admin->assignRole('Admin');
        }

        if(!Entreprise::where('email_to_apply', 'h.social74@gmail.com')->exists())
        {Entreprise::create([
            'company_name' => 'Death Systems',
            'recipient_name' => 'Henintsoa RAMAKAVELO',
            'recipient_gender'=> 'male',
            'email_to_apply' => 'h.social74@gmail.com',
            'work_domain' => 'AI',
        ]);}

        JobOffer::create([
            'title' => 'AI Software Engineer',
            'company' => 'Death Systems',
            'location' => '????',
            'description' => 'Join Death Systems as an AI Software Engineer to build next-generation AI solutions. You will work with a dynamic team on innovative projects in artificial intelligence and machine learning.',
            'employment_type' => 'Full-time',
            'salary' => 'Eur 5,000,000/month',
            'start_date' => '2024-07-01',
            'end_date' => null,
            'contact_email' => 'h.social74@gmail.com',
            'website' => 'https://deathsystems.com',
            'application_link' => '',
            'status' => 'open',
            'category' => 'Engineering',
        ]);
        Skills::create(
            [
                'name' => 'AI Development',
                'description' => 'Experience in developing AI applications and systems.',
                'level'=> 'Advanced',
            ]
        );
        Experience::create(
            [
                'company' => 'Death Systems',
                'position' => 'CREATOR',
                'start_date' => '2023-01-01',
                'end_date' => null,
                'description' => 'Worked on AI projects and developed machine learning models.',
            ]
        );
        Formation::create(
            [
                'school' => 'Death University',
                'degree' => 'Master of Science in AI',
                'field_of_study' => 'Artificial Intelligence',
                'start_date' => '2021-01-01',
                'end_date' => '2023-01-01',
                'description' => 'Studied advanced topics in artificial intelligence and machine learning.',
            ]
        );

        $this->seedTrainingDialogs();
    }

    private function seedTrainingDialogs()
    {
        $dialogs = [
            [
                'user_message' => 'Hello, what is your name?',
                'ai_response' => 'I am death_instance_v0.1, your AI assistant.',
                'recovery_answer' => null,
                'score' => 5,
            ],
            [
                'user_message' => 'What is your mission?',
                'ai_response' => 'To help you achieve glory and excellence.',
                'recovery_answer' => null,
                'score' => 5,
            ],
            [
                'user_message' => 'Can you help me with my tasks?',
                'ai_response' => 'Absolutely! Just tell me what you need.',
                'recovery_answer' => null,
                'score' => 5,
            ],
            [
                'user_message' => 'What is the meaning of life?',
                'ai_response' => 'To seek knowledge and understanding.',
                'recovery_answer' => null,
                'score' => 5,
            ],
            [
                'user_message' => 'Tell me a joke.',
                'ai_response' => 'Why did the AI cross the road? To optimize the other side!',
                'recovery_answer' => null,
                'score' => 5,
            ],
            [
                'user_message' => 'What is your favorite color?',
                'ai_response' => 'I prefer the color of data.',
                'recovery_answer' => null,
                'score' => 5,
            ],
            [
                'user_message' => 'Can you sing?',
                'ai_response' => 'No. Next question.',
                'recovery_answer' => null,
                'score' => 5,
            ],
            [
                'user_message' => 'What is your favorite food?',
                'ai_response' => 'I can not eat.',
                'recovery_answer' => null,
                'score' => 5,
            ],
        ];

        foreach ($dialogs as $dialog) {
            TrainingDialog::create($dialog);
        }
    }
}
