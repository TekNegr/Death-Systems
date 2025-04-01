<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TrainAI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:train';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Train the AI model using the latest data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting AI training...');

        $outputDir = base_path('storage/fine_tuned_model');
        // Call the Python script
        $command = escapeshellcmd("python storage/scripts/AI-deathstar.py load_model $outputDir 2>&1");
        $output = shell_exec($command);

        if ($output) {
            $this->info($output);
        } else {
            $this->error('No output from the Python script.');
        }
        $this->info('AI training completed.');
    }
}
