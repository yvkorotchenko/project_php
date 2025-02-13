<?php

declare(strict_types=1);

namespace App\Chat\Commands;

use App\Chat\Services\Server as ServerService;
use Illuminate\Console\Command;
use Workerman\Worker;

class Server extends Command
{
    private const PID_FILE = '/logs/chat.pid';
    private const LOG_FILE = '/logs/chat.log';
    private const STD_OUT_FILE = '/tmp/stdout.log';
    private const AVAILABLE_ACTIONS = [
        'status',
        'start',
        'stop',
        'restart',
        'reload',
        'connections',
    ];

    private ServerService $server;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:server {action} {--d}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a chat web-socket';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ServerService $server)
    {
        parent::__construct();
        $this->server = $server;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        global $argv;

        if (!\in_array($this->argument('action'), self::AVAILABLE_ACTIONS)) {
            $this->error('action is undefined');
            $this->line('Usage: {' . \implode('|', self::AVAILABLE_ACTIONS) . '} {--d}');
        }

        $argv[0] = 'gateway-worker chat';
        $argv[1] = $this->argument('action');
        $argv[2] = $this->option('d') ? '-d' : '';

        define('GLOBAL_START', 1);
        Worker::$pidFile = storage_path(self::PID_FILE);
        Worker::$stdoutFile = self::STD_OUT_FILE;
        Worker::$logFile = storage_path(self::LOG_FILE);

        $this->server->serve();

        return 0;
    }
}
