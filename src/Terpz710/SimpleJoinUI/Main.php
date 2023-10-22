<?php

declare(strict_types=1);

namespace Terpz710\SimpleJoinUI;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {

    private $hasSeenForm = [];
    private $playerData;

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();

        $this->playerData = new Config($this->getDataFolder() . "player_data.yml", Config::YAML);
        $this->hasSeenForm = $this->playerData->get("players", []);
    }

    public function onPlayerJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $playerName = $player->getName();

        if (!in_array($playerName, $this->hasSeenForm)) {
            $this->sendJoinForm($player);
            $this->hasSeenForm[] = $playerName;
            $this->playerData->set("players", $this->hasSeenForm);
            $this->playerData->save();
        }
    }

    public function sendJoinForm(Player $player) {
        $config = $this->getConfig()->get("messages");

        $this->openForm($player, $config);
    }

    public function openForm(Player $player, array $config) {
        $content = $config["content"];
        $replacedContent = array_map(function ($line) use ($player) {
            return str_replace("{player}", $player->getName(), $line);
        }, $content);

        $form = new SimpleForm(function (Player $player, $data) use ($config) {
            if ($data !== null) {
                if ($data === 0) {
                    $this->playPopSound($player);
                    $this->sendTitle($player, $config["title_on_click"]);
                    $this->sendSubtitle($player, $config["subtitle_text"]);
                }
            } else {
                $player->sendMessage(TextFormat::colorize($config["must_click_ok_message"]));
                $this->sendJoinForm($player);
            }
        });

        $form->setTitle($config["title"]);
        $form->setContent(implode("\n", $replacedContent));

        $buttons = $config["buttons"];
        $form->addButton($buttons[0]);
        $player->sendForm($form);
    }

    public function playPopSound(Player $player) {
        $pk = new PlaySoundPacket();
        $pk->soundName = "random.pop";
        $location = $player->getLocation();
        $pk->x = $location->getX();
        $pk->y = $location->getY();
        $pk->z = $location->getZ();
        $pk->volume = 1.0;
        $pk->pitch = 1.0;
        $player->getNetworkSession()->sendDataPacket($pk);
    }

    public function sendTitle(Player $player, string $titleText) {
        $player->sendTitle(TextFormat::colorize($titleText), "");
    }

    public function sendSubtitle(Player $player, string $subtitleText) {
        $player->sendSubTitle(TextFormat::colorize(str_replace("{player}", $player->getName(), $subtitleText)));
    }

    public function onPlayerCloseForm(Player $player, ?SimpleForm $form) {
        if ($form === null) {
            $config = $this->getConfig()->get("messages");
            $player->sendMessage(TextFormat::colorize($config["must_click_ok_message"]));
            $this->sendJoinForm($player);
        }
    }
}
