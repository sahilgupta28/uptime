<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Components\UptimeChecker;
use App\Components\FirebaseDB;

class CheckUptime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uptime:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check uptime of all sites';
    protected $fc;
    protected $uc;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->fc = new FirebaseDB();
        $this->uc = new UptimeChecker();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sites = $this->getAllSites();
        $output = [];
        foreach ($sites as $site) {
            $uc_status = $this->uc->start($site['site_url']);
            $output[$site['id']] = $uc_status ? $site['site_name'] . ' is up.' : $site['site_name'] . ' is down.';
        }
        \Log::info($output);
    }

    private function getAllSites()
    {
        $data = [
            'collection' => 'sites',
            'limit' => 100
        ];
        return $this->fc->fr($data);
    }
}
