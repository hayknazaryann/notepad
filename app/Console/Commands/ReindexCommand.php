<?php

namespace App\Console\Commands;

use App\Models\Note;
use Elastic\Elasticsearch\Client;
use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /** @var Client */
    private $elasticsearch;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $elasticsearch)
    {
        parent::__construct();
        $this->elasticsearch = $elasticsearch;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Indexing all articles. This might take a while...');
        foreach (Note::cursor() as $note)
        {
            $this->elasticsearch->index([
                'index' => $note->getSearchIndex(),
                'type' => $note->getSearchType(),
                'id' => $note->getKey(),
                'body' => $note->toSearchArray(),
            ]);
            $this->output->write('.');
        }
        $this->info('\nDone!');
    }
}
