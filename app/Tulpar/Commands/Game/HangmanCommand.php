<?php


namespace App\Tulpar\Commands\Game;


use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Channel\Message;
use stdClass;

class HangmanCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'hangman';

    public static string $description = 'The hangman game.';

    public static array $permissions = [];

    public static array $steps = [
        ' ------
 |/    |
 |
 |
 |
/|\\',
        ' ------
 |/    |
 |     o
 |
 |
/|\\',
        ' ------
 |/    |
 |     o
 |     |
 |
/|\\',
        ' ------
 |/    |
 |     o
 |     |
 |    /
/|\\',
        ' ------
 |/    |
 |     o
 |     |
 |    / \
/|\\',
        ' ------
 |/    |
 |     o
 |   --|
 |    / \
/|\\',
        ' ------
 |/    |
 |     o
 |   --|--
 |    / \
/|\\'
    ];

    public static array $games = [];

    public static function randomWord(): string
    {
        $words = [
            'tulpar',
            'discord',
            'kelime',
            'hazine',
            'mağara',
            'bebek',
            'nur',
        ];

        return $words[array_rand($words)];
    }

    public static function draw(object $game): string
    {
        return '```' . static::$steps[$game->attemps] . '```';
    }

    public static function text(object $game): string
    {
        $len = strlen($game->word);
        $string = str_repeat('_ ', $len);

        for ($i = 0; $i < $len; $i++) {
            $ch = $game->word[$i];
            if (strstr($game->found, $ch)) {
                $pos = 2 * $i;
                $string[$pos] = $ch;
            }
        }

        $string .= '  ' . PHP_EOL . '  Attempts: ' . $game->attemps . '  ';
        return '``  ' . $string . '``';
    }

    public static function getInstance(Channel $channel)
    {
        if (!array_key_exists($channel->id, static::$games)) {
            $game = new stdClass;
            $game->image = null;
            $game->message = null;
            $game->word = static::randomWord();
            $game->found = ' ';
            $game->attemps = 0;
            static::$games[$channel->id] = $game;

            $channel->sendMessage(static::draw($game))->done(function (Message $message) use ($game, $channel) {
                static::$games[$channel->id]->image = $message;

                $channel->sendMessage(static::text($game))->done(function (Message $message) use ($channel) {
                    static::$games[$channel->id]->message = $message;
                });
            });
        }

        return static::$games[$channel->id];
    }

    public function run(): void
    {
        $action = $this->userCommand->getArgument(0);

        if ($action != 'create' && !array_key_exists($this->message->channel->id, static::$games)) {
            $this->message->channel->sendMessage('You need create a game first.');
            return;
        }

        if ($action == 'create') {
            static::getInstance($this->message->channel);
            return;
        }

        if ($action == 'restart') {
            if (array_key_exists($this->message->channel->id, static::$games)) {
                unset(static::$games[$this->message->channel->id]);
            }

            static::getInstance($this->message->channel);
            return;
        }

        if ($action == 'try') {
            $character = $this->userCommand->getArgument(1);
            $game = static::getInstance($this->message->channel);
            $gameOver = false;

            if (!strstr($game->word, $character)) {
                $game->attemps++;

                if ($game->attemps >= 6) {
                    $gameOver = true;
                }
            } else {
                $game->found .= $character;
            }

            /** @var Message $message */
            $message = $game->image;
            $message->content = static::draw($game);
            $message->channel->messages->save($message)->done(function () use ($game, $gameOver) {
                /** @var Message $message */
                $message = $game->message;
                $message->content = static::text($game);

                $winner = false;
                if (!$gameOver) {
                    if (!strstr($message->content, '_')) {
                        $winner = true;
                    }
                }

                $message->channel->messages->save($message)->done(function () use ($message, $gameOver, $winner) {
                    if ($gameOver) {
                        $message->channel->sendMessage('Game Over!');
                        unset(static::$games[$this->message->channel->id]);
                    } else if ($winner) {
                        $message->channel->sendMessage('Congratulations!');
                        unset(static::$games[$this->message->channel->id]);
                    }
                });
            });

            $this->message->delete();
        }
    }
}
