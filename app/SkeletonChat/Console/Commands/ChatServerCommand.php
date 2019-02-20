<?php

namespace SkeletonChatApp\Console\Commands;

use SkeletonChatApp\Chat;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use SkeletonCore\BaseCommand;
use Symfony\Component\Console\Input\InputInterface as Input;
use Symfony\Component\Console\Output\OutputInterface as Output;

class ChatServerCommand extends BaseCommand
{
    /**
     * The command signature.
     *
     * @var string
     */
    private $signature = "serve:chat {--host= : Chat host name} {--port= : Chat port}";

    /**
     * The command description.
     *
     * @var string
     */
    private $description = "Serve the chat server.";

    /**
     * Create a new command instance
     */
    public function __construct()
    {
        parent::__construct($this->signature, $this->description);
    }

    /**
     * To be call after execute the command.
     *
     * @param  Input $input
     * @param  Output $output
     * @return void
     */
    public function handle(Input $input, Output $output)
    {
        $host = !is_null($input->getOption('host')) ? $input->getOption('host') : config('sklt-chat.host');
        $port = !is_null($input->getOption('port')) ? $input->getOption('port') : config('sklt-chat.port');

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Chat
                )
            ),
            $port,
            $host
        );

        $server->run();
    }
}
