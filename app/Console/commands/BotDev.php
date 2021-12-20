<?php

namespace App\Console\Commands;

use App\Services\TelegramServices\Telegram\Api;
use App\Services\TelegramServices\Telegram\WebhookUpdates;
use App\Services\TelegramServices\TelegramService;
use Illuminate\Console\Command;

class BotDev extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the bot';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Bot ishga tushdi");
        $bot = new Api();
        $updates = $bot->getUpdates();
        $update_id = !empty($updates['result']) ? end($updates['result'])['update_id'] : 0;
        start:
        $updates = $bot->getUpdates(['offset' => $update_id]);
        foreach ($updates['result'] as $update) {
            if ($update_id < $update['update_id']) {
                $update_id = $update['update_id'];
                $message = new WebhookUpdates($update, 'user');
                $start = new TelegramService($bot, $message);
                $start->index();
            }

        }
        goto start;

    }
}
