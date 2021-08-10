<?php

namespace App\Tulpar\Commands\Development;

use App\Enums\WorkAction;
use App\Models\CustomCommand;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Workflow\Work;
use App\Tulpar\Youtube;

class TestCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'test';

    public static string $description = 'The test command';

    public static array $permissions = ['root'];

    public function run(): void
    {
        $search = Youtube::search($this->userCommand->getArgument(0), max_result: 1)->first();
        $id = $search->id->videoId;
        $title = $search->snippet->title;
        $description = $search->snippet->description;
        $thumbnails = $search->snippet->thumbnails;

        $this->message->channel->sendMessage('downloading: ' . $title);
        Youtube::download($id);
        return;

        $works = [];

        $work = new Work;
        $work->action = WorkAction::Anything;
        $works[] = $work;

        $work = new Work;
        $work->action = WorkAction::SendMessage;
        $work->data = ['message' => 'deneme'];
        $works[] = $work;

        $work = new Work;
        $work->action = WorkAction::SendMessage;
        $work->data = ['message' => 'xd'];
        $works[] = $work;

        $work = new Work;
        $work->action = WorkAction::SendMessage;
        $work->data = ['message' => 'XDDDD'];
        $works[] = $work;

        $work = new Work;
        $work->action = WorkAction::SendMessageAnotherChannel;
        $work->data = ['message' => 'xd', 'channel_id' => '871304143552262184'];
        $works[] = $work;

        $work = new Work;
        $work->action = WorkAction::GiveRole;
        $work->data = ['role_id' => '871199716380123137'];
        $works[] = $work;

        $work = new Work;
        $work->action = WorkAction::SendMessage;
        $work->data = ['message' => '.dd.d.d.d.'];
        $works[] = $work;

        CustomCommand::create([
            'guild_id' => $this->message->guild_id,
            'command' => 'deneme',
            'workflow' => serialize($works),
        ]);
    }
}
