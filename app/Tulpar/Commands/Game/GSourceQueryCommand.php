<?php


namespace App\Tulpar\Commands\Game;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use Illuminate\Support\Str;
use Discord\Parts\Embed\Embed;
use GuzzleHttp\Client;
use xPaw\SourceQuery\SourceQuery;
	
class GSourceQueryCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'sourcequery';

    public static string $description = 'server info';

    public static array $permissions = [];

    public static bool $allowPm = true;

    public static array $usages = [''];

    public static string $version = '1.0';
	
	public static array $requires = [0, 1];

    public static string $category = CommandCategory::Game;

    public function run(): void
    {
	$ip = $this->userCommand->getArgument(0);
	$port = $this->userCommand->getArgument(1);
	
	// Edit this ->
	$SQ_TIMEOUT = 1;
	$SQ_ENGINE = SourceQuery::GOLDSOURCE;
	// Edit this <-
	$Timer = microtime( true );
	
	$Query = new SourceQuery( );
	
	$Info    = [];
	$Rules   = [];
	$Players = [];
	$Exception = null;
	
		$Query->Connect( $ip, $port, $SQ_TIMEOUT, $SQ_ENGINE );
		$Query->SetUseOldGetChallengeMethod( true); // Use this when players/rules retrieval fails on games like Starbound
		
		$Info    = $Query->GetInfo( );
	$Timer = number_format( microtime( true ) - $Timer, 4, '.', '' );
	$gamedir = match ($Info["ModDir"]) {
		default =>  $this->translate('Other'),
		'svencoop' => 'Sven Co-op',
		'garrysmod' => 'Garry\'s Mod',
		'valve' => 'Half-Life',
		'cstrike' => 'Counter-Strike 1.6',
	};
		$embed = new Embed($this->discord);
        $embed->setAuthor($this->message->user->username, $this->message->user->avatar);
        $embed->setThumbnail("https://yt3.ggpht.com/ytc/AKedOLRcv_UAxEe4LS1FHQuaNCuNWDAUHu-TI5J2R36I=s900-c-k-c0x00ffffff-no-rj");
        $embed->addFieldValues($this->translate('Server Name'), $Info["HostName"], true);
        $embed->addFieldValues($this->translate('Game'), $gamedir, true);
        $embed->addFieldValues($this->translate('Players'),$Info["Players"] .'/'.$Info["MaxPlayers"], true);
        $embed->addFieldValues($this->translate('Map'), $Info["Map"], true);
        $embed->addFieldValues($this->translate('Description'), $Info["ModDesc"], true);

        $this->message->channel->sendEmbed($embed);
		
		$Query->Disconnect( );
	
    }
}
