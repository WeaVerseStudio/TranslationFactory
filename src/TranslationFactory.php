<?php

declare(strict_types=1);

namespace PrograMistV1;

use pocketmine\lang\KnownTranslationKeys;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use Symfony\Component\Filesystem\Path;

class TranslationFactory extends PluginBase{

    private const DEFAULT_LANG = "en_us";

    /**
     * @link https://wiki.bedrock.dev/concepts/text-and-translations.html#vanilla-languages
     */
    public const ALL_LANG_CODES = [
        "id_id" => "Indonesian",
        "da_dk" => "Danish",
        "de_de" => "German",
        "en_gb" => "English",
        "en_us" => "English",
        "es_es" => "Spanish",
        "es_mx" => "Mexican Spanish",
        "fr_ca" => "Canadian French",
        "fr_fr" => "French",
        "it_it" => "Italian",
        "hu_hu" => "Hungarian",
        "nl_nl" => "Dutch",
        "nb_nb" => "Bokmål",
        "pl_pl" => "Polish",
        "pt_br" => "Brazilian Portuguese",
        "pt_pt" => "Portuguese",
        "sk_sk" => "Slovak",
        "fi_fi" => "Finnish",
        "sv_se" => "Swedish",
        "tr_tr" => "Turkish",
        "cs_cz" => "Czech",
        "el_gr" => "Greek",
        "bg_bg" => "Bulgarian",
        "ru_ru" => "Russian",
        "uk_ua" => "Ukrainian",
        "ja_jp" => "Japanese",
        "zh_cn" => "Chinese (Simplified)",
        "zh_tw" => "Chinese (Traditional)",
        "ko_kr" => "Korean"
    ];

    /**
     * @var Language[]
     */
    private static array $languages = [];
    private static array $plugins = [];

    protected function onLoad() : void{
        @mkdir(Path::join($this->getDataFolder(), "players"));
        foreach(self::ALL_LANG_CODES as $langCode => $lang){
            self::$languages[$langCode] = new Language($langCode, self::DEFAULT_LANG);
        }
    }

    public static function addPluginTranslations(PluginBase $plugin) : void{
        if(isset(self::$plugins[$plugin::class])){
            return;
        }
        self::$plugins[$plugin::class] ??= true;

        $path = Path::join($plugin->getDataFolder(), "languages");
        self::generateLanguageFiles($path);
        $languages = array_intersect_key(Language::getLanguageList($path), self::ALL_LANG_CODES);

        foreach($languages as $langCode => $lang){
            self::$languages[$langCode]->addTranslations($path, $langCode);
        }
    }

    public static function translate(Player $player, Translatable $translatable) : string{
        $playerLang = strtolower($player->getLocale());
        if(in_array($playerLang, array_keys(self::$languages))){
            return self::$languages[$playerLang]->translate($translatable);
        }
        return self::$languages[self::DEFAULT_LANG]->translate($translatable);
    }

    private static function generateLanguageFiles(string $dataPath) : void{
        foreach(self::ALL_LANG_CODES as $langCode => $language){
            $path = Path::join($dataPath, $langCode . ".ini");
            if(!file_exists($path)){
                @mkdir($dataPath);
                $file = fopen($path, "w");
                fwrite($file, KnownTranslationKeys::LANGUAGE_NAME . "=" . $language);
                fclose($file);
            }
        }
    }
}
