<?php

namespace App\Tulpar\Discord;

use Discord\Exceptions\IntentException;
use Discord\Factory\Factory;
use Discord\Http\Drivers\React;
use Discord\Parts\User\Client;
use Discord\WebSockets\Handlers;
use Discord\WebSockets\Op;
use Ratchet\Client\Connector;
use React\Socket\Connector as SocketConnector;

class Discord extends \Discord\Discord
{
    /**
     * Creates a Discord client instance.
     *
     * @param array $options Array of options.
     * @throws IntentException
     */
    public function __construct(array $options = [])
    {
        if (php_sapi_name() !== 'cli') {
            trigger_error('Tulpar bot is can be only run in CLI. Please use PHP CLI.', E_USER_ERROR);
        }

        $options = $this->resolveOptions($options);

        $this->options = $options;
        $this->token = $options['token'];
        $this->loop = $options['loop'];
        $this->logger = $options['logger'];

        $connector = new SocketConnector($this->loop, $options['socket_options']);
        $this->wsFactory = new Connector($this->loop, $connector);
        $this->handlers = new Handlers();

        foreach ($options['disabledEvents'] as $event) {
            $this->handlers->removeHandler($event);
        }

        $function = function () use (&$function) {
            $this->emittedReady = true;
            $this->removeListener('ready', $function);
        };

        $this->on('ready', $function);

        $this->http = new Http(
            'Bot ' . $this->token,
            $this->loop,
            $this->options['logger'],
            new React($this->loop, $options['socket_options'])
        );

        $this->factory = new Factory($this, $this->http);
        $this->client = $this->factory->create(Client::class, [], true);

        $this->connectWs();
    }

    /**
     * Identifies with the Discord gateway with `IDENTIFY` or `RESUME` packets.
     *
     * @param bool $resume Whether resume should be enabled.
     * @return bool
     */
    protected function identify(bool $resume = true): bool
    {
        if ($resume && $this->reconnecting && !is_null($this->sessionId)) {
            $payload = [
                'op' => Op::OP_RESUME,
                'd' => [
                    'session_id' => $this->sessionId,
                    'seq' => $this->seq,
                    'token' => $this->token,
                ],
            ];

            $reason = 'resuming connection';
        }
        else {
            $payload = [
                'op' => Op::OP_IDENTIFY,
                'd' => [
                    'token' => $this->token,
                    'properties' => [
                        '$os' => PHP_OS,
                        '$browser' => $this->http->getUserAgent(),
                        '$device' => $this->http->getUserAgent(),
                        '$referrer' => 'https://tulpar.xyz',
                        '$referring_domain' => 'https://tulpar.xyz',
                    ],
                    'compress' => true,
                    'intents' => $this->options['intents'],
                ],
            ];

            if (
                array_key_exists('shardId', $this->options) &&
                array_key_exists('shardCount', $this->options)
            ) {
                $payload['d']['shard'] = [
                    (int)$this->options['shardId'],
                    (int)$this->options['shardCount'],
                ];
            }

            $reason = 'identifying';
        }

        $safePayload = $payload;
        $safePayload['d']['token'] = 'xxxxxx';

        $this->logger->info($reason, ['payload' => $safePayload]);

        $this->send($payload);

        return $payload['op'] == Op::OP_RESUME;
    }
}
