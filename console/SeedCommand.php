<?php namespace Tb\Catalog\Console;

use Tb\Catalog\Database\Seeders\DatabaseSeeder;
use Winter\Storm\Console\Command;

class SeedCommand extends Command
{
    protected static $defaultName = 'catalog:seed';

    protected $description = 'Seed the Tb.Catalog plugin data';

    public function handle()
    {
        $this->info('Seeding Tb.Catalog data…');
        (new DatabaseSeeder)->run();
        $this->info('Done.');
    }
}
