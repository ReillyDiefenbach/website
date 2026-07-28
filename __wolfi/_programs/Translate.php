<?php

class Language {
    private $area;
    private $lang;
    private $defaultLang;
    private $translations;

    public function __construct($area = 'A', $lang = 'en', $defaultLang = 'en') {
        $this->area = $area;
        $this->lang = $lang;
        $this->defaultLang = $defaultLang;
        $this->translations = $this->loadTranslations();
    }

    private function loadTranslations() {
        $translationsDir = LANGPATH . "{$this->area}/";
        
        // Load default language file
        $defaultFile = "{$translationsDir}{$this->defaultLang}.json";
        if (!file_exists($defaultFile)) {
            error_log("Default language file not found for area: {$this->area}");
            return [];
        }
        $defaultTranslations = json_decode(file_get_contents($defaultFile), true);
        
        // Load requested language file
        $langFile = "{$translationsDir}{$this->lang}.json";
        if (file_exists($langFile)) {
            $langTranslations = json_decode(file_get_contents($langFile), true);
            // Merge translations with default translations
            return array_merge($defaultTranslations, $langTranslations);
        } else {
            error_log("Language file not found for area: {$this->area}, language: {$this->lang}");
            return $defaultTranslations;
        }
    }

    public function getTranslations() {
        return $this->translations;
    }

    public function getTranslation($key) {
        if (isset($this->translations[$key])) {
            return $this->translations[$key];
        } else {
            error_log("Translation key '$key' not found in area: {$this->area}, language: {$this->lang}");
            return null;
        }
    }
}