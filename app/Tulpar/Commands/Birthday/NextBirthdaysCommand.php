<?php


namespace App\Tulpar\Commands\Birthday;


use App\Enums\CommandCategory;
use App\Models\Birthday;
use App\Tulpar\Commands\BaseCommand;
use App\Tulpar\Contracts\CommandInterface;
use App\Tulpar\Helpers;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Embed\Embed;
use Illuminate\Support\Carbon;

class NextBirthdaysCommand extends BaseCommand implements CommandInterface
{
    public static string $command = 'next-birthdays';

    public static string $description = 'List up to 10 upcoming birthdays.';

    public static array $permissions = ['*'];

    public static array $usages = [''];

    public static array $requires = [];

    public static string $version = '1.0';

    public static string $category = CommandCategory::Chat;

    public function run(): void
    {
        $birthdays = collect();
        $now = now();

        $query = Birthday::where('server_id', $this->message->guild_id)->get();
        foreach ($query as $birthday) {
            $original = new Carbon(sprintf('%s-%s-%s', $birthday->year, $birthday->month, $birthday->day));
            $thisYear = new Carbon(sprintf('%s-%s-%s', date('Y'), $birthday->month, $birthday->day));
            $nextYear = new Carbon(sprintf('%s-%s-%s', intval(date('Y')) + 1, $birthday->month, $birthday->day));
            $year = $thisYear;
            if ($now->diff($thisYear)->invert) {
                $year = $nextYear;
            }

            if ($now->diff($year)->days < 11) {
                $birthdays->add((object)[
                    'member_id' => $birthday->member_id,
                    'date' => $year,
                ]);
            }
        }

        $birthdays = $birthdays->sortBy([
            function ($a, $b) use ($now) {
                $_a = $now->diff($a->date);
                $_a = $_a->y . $_a->m . $_a->d . $_a->h . $_a->i . $_a->s;

                $_b = $now->diff($b->date);
                $_b = $_b->y . $_b->m . $_b->d . $_b->h . $_b->i . $_b->s;

                return $_a <=> $_b;
            },
        ]);

        $message = MessageBuilder::new()
            ->setReplyTo($this->message)
            ->setContent($this->translate('The list of 10 upcoming birthdays.'));

        foreach ($birthdays as $birthday) {
            $embed = new Embed($this->discord);
            $embed->setDescription(Helpers::userTag($birthday->member_id));
            $embed->addFieldValues($this->translate('Year'), $birthday->date->year);
            $embed->addFieldValues($this->translate('Month'), $birthday->date->month);
            $embed->addFieldValues($this->translate('Day'), $birthday->date->day);
            $message->addEmbed($embed);
        }

        $this->message->channel->sendMessage($message);
    }
}
