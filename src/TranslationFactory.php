<?php

declare(strict_types=1);

namespace PrograMistV1;

use pocketmine\lang\Language;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class TranslationFactory extends PluginBase{

    private const DEFAULT_LANG = "en_us";

    /**
     * @var Language[]
     */
    private static array $languages = [];

    protected function onEnable() : void{
        $this->saveResource(self::DEFAULT_LANG.".ini");
        $files = scandir($this->getDataFolder());
        foreach($files as $file){
            if(preg_match('/\.(ini)/', $file)){
                $langCode = str_replace(".ini", "", $file);
                self::$languages[$langCode] = new Language($langCode, $this->getDataFolder(), self::DEFAULT_LANG);
            }
        }
    }

    public static function translate(Player $player, Translatable $translatable) : string{
        $playerLang = strtolower($player->getPlayerInfo()->getExtraData()["LanguageCode"]);
        if(in_array($playerLang, array_keys(self::$languages))){
            return self::$languages[$playerLang]->translate($translatable);
        }
        return self::$languages[self::DEFAULT_LANG]->translate($translatable);
    }
}