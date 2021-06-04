<?php


namespace BadChoice\Thrust\Helpers;


class Translation
{
    public static function useTranslationPrefix($translationMessage, $noTranslationResult = null) {
        $translationPrefix = config('thrust.translationsPrefix');
        if ( $translationMessage && (strpos(__($translationPrefix.$translationMessage), $translationPrefix) === false) ) {
            return __($translationPrefix.$translationMessage);
        }
        return $noTranslationResult;
    }
}