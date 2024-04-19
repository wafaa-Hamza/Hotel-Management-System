<?php

namespace App\Console\Commands;

use  App\Models\ApiKey;
use Illuminate\Console\Command;

class ListApiKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apikey:listkey {--D|deleted}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all API Keys';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $keys = $this->option('deleted')
            ? ApiKey::withTrashed()->orderBy('name')->get()
            : ApiKey::orderBy('name')->get();

        if ($keys->count() === 0) {
            $this->info('There are no API keys');
            return false;
        }

        $headers = ['Name', 'ID', 'Status', 'domain','Status Date', 'expire_date', 'Key'];

        $rows = $keys->map(function($key) {

            $status = $key->active    ? 'active'  : 'deactivated';
            $status = $key->trashed() ? 'deleted' : $status;

            $statusDate = $key->deleted_at ?: $key->updated_at;

            return [
                $key->name,
                $key->id,
                $status,
                $key->domain,
                $statusDate,
                $key->expired_date,
                $key->key
            ];

        });

        $this->table($headers, $rows);
    }
}