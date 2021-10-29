<?php


namespace App\Tulpar\Commands\Game;


use App\Enums\CommandCategory;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use Illuminate\Support\Str;
use Discord\Parts\Embed\Embed;
use xPaw\SourceQuery\SourceQuery;
	
class GoldSrcCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'svencoopserver';

    public static string $description = 'Sven Co-op sunucu bilgilerini getirir.';

    public static array $permissions = [];

    public static bool $allowPm = true;

    public static array $usages = [''];

    public static string $version = '1.0';

    public static string $category = CommandCategory::Game;

    public function run(): void
    {
	// Edit this ->
	$SQ_SERVER_ADDR = "193.164.7.208";
	$SQ_SERVER_PORT = "27015";
	$SQ_TIMEOUT = 1;
	$SQ_ENGINE = SourceQuery::SOURCE;
	// Edit this <-
		$Timer = microtime( true );
	
	$Query = new SourceQuery( );
	
	$Info    = [];
	$Rules   = [];
	$Players = [];
	$Exception = null;
	
	try
	{
		$Query->Connect( $SQ_SERVER_ADDR, $SQ_SERVER_PORT, $SQ_TIMEOUT, $SQ_ENGINE );
		//$Query->SetUseOldGetChallengeMethod( true ); // Use this when players/rules retrieval fails on games like Starbound
		
		$Info    = $Query->GetInfo( );
		$Players = $Query->GetPlayers( );
		$Rules   = $Query->GetRules( );
	}
	catch( Exception $e )
	{
		$Exception = $e;
	}
	finally
	{
		$Query->Disconnect( );
	}
	
	$Timer = number_format( microtime( true ) - $Timer, 4, '.', '' );
	$oyunculistesi = "Sunucu Boş!";
	foreach ($Players as $Player) {
		$oyunculistesi.= $Player["Name"] . "\r\n";
	}
	$gamedir = match ($Info["ModDir"]) {
		default =>  $this->translate('Other'),
		'svencoop' => 'Sven Co-op',
	};
	
		$embed = new Embed($this->discord);
        $embed->setAuthor($this->message->user->username, $this->message->user->avatar);
        $embed->setThumbnail("https://yt3.ggpht.com/ytc/AKedOLRcv_UAxEe4LS1FHQuaNCuNWDAUHu-TI5J2R36I=s900-c-k-c0x00ffffff-no-rj");
        $embed->addFieldValues($this->translate('Server Name'), $Info["HostName"], true);
        $embed->addFieldValues($this->translate('Game'), $gamedir, true);
        $embed->addFieldValues($this->translate('Players'),$Info["Players"] .'/'.$Info["MaxPlayers"], true);
        $embed->addFieldValues($this->translate('Player List'),$oyunculistesi, true);
        $embed->addFieldValues($this->translate('Map'), $Info["Map"], true);
        $embed->addFieldValues($this->translate('Description'), $Info["ModDesc"], true);

        $this->message->channel->sendEmbed($embed);
    }
}
