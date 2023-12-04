<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Gate;

class Gates extends Command
{
    protected $signature   = 'app:gates-list';
    protected $description = 'List of all available Gates';

    public function handle()
    {
        print PHP_EOL . $this->description . PHP_EOL;

        $gates = Gate::abilities();

        foreach ($gates as $abilitie => $closure)
        {
            print PHP_EOL . $abilitie . PHP_EOL;
        }
    }
}
