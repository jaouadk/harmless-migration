<?php

namespace Shincoder\Harmless\Commands;

use Illuminate\Console\Command;
use Shincoder\Harmless\Factory;
use Shincoder\Harmless\DatabaseStub;

class MigrateTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:test
                            {--database= : List of comma separated database connections}';

    protected $factory;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test database migrations';

    public function __construct(Factory $factory)
    {
        parent::__construct();

        $this->factory = $factory;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     *
     * TODO: add --with-data option
     */
    public function handle()
    {
        $connections = $this->getTargetConnections();
        $stubs = [];

        try {
            foreach ($connections as $name) {
                $stub = $this->factory->makeStub($name);

                $this->info('Created testing database: '.$stub->getDatabaseName());

                $stubs[] = $stub;
            }

            $this->info('Running artisan migrate against the testing database(s)');

            $exitCode = $this->call('migrate');

            $this->destroyStubs($stubs);

            if ($exitCode != 0) {
                $this->error('You recent migrations will break your database');
            } else {
                $this->info('Looks good, you can migrate for real now!');
            }
        } catch (\Exception $e) {
            $this->warn('Something went wrong!!! Cleaning...');

            $this->destroyStubs($stubs);

            throw $e;
        }
    }

    protected function destroyStubs($stubs)
    {
        foreach ($stubs as $stub) {
            $this->info('Dropping stub: '.$stub->getDatabaseName());

            $stub->drop();
        }
    }

    protected function getTargetConnections()
    {
        $databases = $this->option('database');

        if (!empty($databases)) {
            return explode(',', $databases);
        }

        return [config('database.default')];
    }
}
