<?php

namespace BadChoice\Thrust\Helpers;

trait Titleable {

    public $title = null;
    public $translationStrategies = [
        'translatedTitle',
        'rawTitle',
        'translatedClassNameTitle',
        'niceClassNameTitle'
    ];

    public function getTitle(){
        return collect($this->translationStrategies)->firstMap(function($functionName) {
            return $this->$functionName();
        });
	}

    private function translatedTitle() {
        return ($this->title && (strpos(__($this->getTranslationPrefix().$this->title), $this->getTranslationPrefix()) === false)) ? __($this->getTranslationPrefix().$this->title) : null;
	}

	private function rawTitle() {
        return $this->title ?? null;
    }

    private function translatedClassNameTitle() {
        $ucClassName = lcfirst($this->sanitizedClassName());
        return strpos(__($this->getTranslationPrefix().$ucClassName), $this->getTranslationPrefix()) === false ? __($this->getTranslationPrefix().$ucClassName) : null;
	}

    private function niceClassNameTitle() {
        return $this->niceTitle($this->sanitizedClassName());
	}

	private function sanitizedClassName() {
        return collect(explode('\\', get_class($this)))->last();
    }

    private function niceTitle($className)
    {
        $parts    = explode(' ', $className);
        $parts[0] = preg_replace('~([a-z])([A-Z])~', '\\1 \\2', $parts[0]);
        return implode(' ', $parts);
    }

    private function getTranslationPrefix() {
        return config('thrust.translationsPrefix');
    }

}