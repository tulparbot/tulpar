<?php

namespace App\Tulpar;

use Discord\Builders\Components\ActionRow;
use Discord\Builders\Components\Button;
use Discord\Builders\MessageBuilder;
use Discord\Exceptions\IntentException;
use Discord\Parts\Interactions\Interaction;

class Dialog
{
    /**
     * @param string        $content
     * @param bool|int      $styleNo
     * @param bool|int      $styleYes
     * @param callable|null $listenerNo
     * @param callable|null $listenerYes
     * @return MessageBuilder
     * @throws IntentException
     */
    public static function confirm(
        string   $content = 'Are you sure?',
        bool     $styleNo = Button::STYLE_PRIMARY,
        bool     $styleYes = Button::STYLE_DANGER,
        callable $listenerNo = null,
        callable $listenerYes = null,
    ): MessageBuilder
    {
        $discord = Tulpar::getInstance()->getDiscord();
        $builder = MessageBuilder::new();
        $builder->setContent($content);

        $no = Button::new($styleNo)->setLabel('No')->setListener(function (Interaction $interaction) use ($builder, $listenerNo) {
            $listenerNo($interaction, $builder);
        }, $discord);

        $yes = Button::new($styleYes)->setLabel('Yes')->setListener(function (Interaction $interaction) use ($builder, $listenerYes) {
            $listenerYes($interaction, $builder);
        }, $discord);

        $builder->addComponent(ActionRow::new()
            ->addComponent($no)
            ->addComponent($yes)
        );

        return $builder;
    }
}
