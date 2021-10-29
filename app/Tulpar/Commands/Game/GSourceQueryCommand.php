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

    public static string $description = 'Source Engine server info get.';

    public static array $permissions = [];

    public static bool $allowPm = true;

    public static array $usages = [''];

    public static string $version = '1.0';
	
	public static array $requires = [0];

    public static string $category = CommandCategory::Game;

    public function run(): void
    {
	$ip = $this->userCommand->getArgument(0);
	$port = $this->userCommand->getArgument(1);
	
	// Edit this ->
	$SQ_TIMEOUT = 1;
	$SQ_ENGINE = SourceQuery::SOURCE;
	// Edit this <-
	$Timer = microtime( true );
	
	$Query = new SourceQuery( );
	
	$Info    = [];
	$Rules   = [];
	$Players = [];
	$Exception = null;
	
		$Query->Connect( $ip, $port='27015', $SQ_TIMEOUT, $SQ_ENGINE );
		$Query->SetUseOldGetChallengeMethod( true); // Use this when players/rules retrieval fails on games like Starbound
		
		$Info    = $Query->GetInfo( );
	$Timer = number_format( microtime( true ) - $Timer, 4, '.', '' );
	$gamedir = match ($Info["ModDir"]) {
		default =>  $this->translate('Other'),
		'svencoop' => 'Sven Co-op',
		'garrysmod' => 'Garry\'s Mod',
		'valve' => 'Half-Life',
		'cstrike' => 'Counter-Strike',
		'csgo' => 'Counter-Strike: Global Offensive',
		'l4d2' => 'Left 4 Dead 2',
	};
	if ($Info["ModDir"] == "valve"){
		$thumbnail = "https://www.kindpng.com/picc/m/204-2045030_half-life-logo-png-transparent-background-dollar-sign.png";
	}elseif ($Info["ModDir"] == "svencoop") {
		$thumbnail = "https://yt3.ggpht.com/ytc/AKedOLRcv_UAxEe4LS1FHQuaNCuNWDAUHu-TI5J2R36I=s900-c-k-c0x00ffffff-no-rj";
	}elseif($Info["ModDir"] == "cstrike"){
		$thumbnail = "https://icon-library.com/images/counter-strike-icon/counter-strike-icon-3.jpg";
	}elseif ($Info["ModDir"] == "garrysmod"){
		$thumbnail = "https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/95516a47-8cd4-4460-8bde-0392d01ee6f0/d8xd5lx-8917479a-25de-44de-a99b-a1b07a68d5cd.png?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzk1NTE2YTQ3LThjZDQtNDQ2MC04YmRlLTAzOTJkMDFlZTZmMFwvZDh4ZDVseC04OTE3NDc5YS0yNWRlLTQ0ZGUtYTk5Yi1hMWIwN2E2OGQ1Y2QucG5nIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.62CjDA4cRGZTQU_ZsDReQSylixAiVsdmDW5wNdU2nuI";
	}elseif ($Info["ModDir"] == "csgo"){
		$thumbnail = "https://external-preview.redd.it/2C3LuiF_EFkpTxcZ7a3nSI7k8ABzUBIPw40763JnrRs.png?auto=webp&s=3d1b48f4c2ceb533791c7c840bb5db6a49dd2f94";
	}else{
		$thumbnail = "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSOj15lhy_7nSE5m0QHSybEBRJoYdhvy85en11WtZcLVH3JzxZgP10XKk49LckNgU-1wAU&usqp=CAU";

	}
		$embed = new Embed($this->discord);
        $embed->setAuthor($this->message->user->username, $this->message->user->avatar);
        $embed->setThumbnail($thumbnail);
        $embed->addFieldValues($this->translate('Server Name'), $Info["HostName"], true);
        $embed->addFieldValues($this->translate('Game'), $gamedir, true);
        $embed->addFieldValues($this->translate('Players'),$Info["Players"] .'/'.$Info["MaxPlayers"], true);
        $embed->addFieldValues($this->translate('Map'), $Info["Map"], true);
        $embed->addFieldValues($this->translate('Description'), $Info["ModDesc"], true);

        $this->message->channel->sendEmbed($embed);
		
		$Query->Disconnect( );
	
    }
}
