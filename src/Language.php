<?php

declare(strict_types=1);

namespace PrograMistV1;

use pocketmine\lang\Language as BaseLanguage;
use pocketmine\lang\LanguageNotFoundException;

class Language extends BaseLanguage{


    public function __construct(string $lang, private readonly string $fallback){
        try{
            parent::__construct($lang);
        }catch(LanguageNotFoundException){

        }

        $this->lang = [];
        $this->fallbackLang = [];
    }

    public function addTranslations(string $path, string $langCode) : void{
        $this->lang[] = self::loadLang($path, $langCode);
        $this->fallbackLang[] = self::loadLang($path, $this->fallback);
    }

}