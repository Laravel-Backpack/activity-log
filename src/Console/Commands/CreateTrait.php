<?php

namespace Backpack\ActivityLog\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Basset Clear command.
 *
 * @property object $output
 * @property \Illuminate\Console\View\Components\Factory $components
 */
class CreateTrait extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity-log:create-trait';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the LogsActivity default trait';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->components->info('Creating the LogsActivity trait');

        $message = 'Publishing the trait <fg=blue;options=bold>App\Models\Traits\LogsActivity</>';
        $stub = File::get(__DIR__.'/../Stubs/LogsActivity.stub');
        $destination = app_path('Models/Traits/LogsActivity.php');

        // check if exists
        if (File::exists($destination)) {
            $this->components->twoColumnDetail($message, '<fg=yellow;options=bold>ALREADY EXISTS</>');
            $this->newLine();
            return;
        }

        // create trait file
        File::makeDirectory(Str::beforeLast($destination, '/'));
        File::put($destination, $stub);

        $this->components->twoColumnDetail($message, '<fg=green;options=bold>DONE</>');
        $this->newLine();
    }
}
