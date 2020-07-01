<?php

namespace BadChoice\Thrust\Fields;

class Currency extends Decimal
{
    static $formatter;
    static $currency = 'EUR';

    public static function setFormatter($locale, $currency = 'EUR')
    {
        static::$formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        static::$currency  = $currency;
    }

    public function displayInIndex($object)
    {
        if (static::$formatter){
            return static::$formatter->formatCurrency($this->getIndexValue($object) , 'EUR' );
        }
        return number_format($this->getIndexValue($object), 2) . ' €';
    }

    private function getIndexValue($object){
        if ($this->displayInIndexCallback){
            return call_user_func($this->displayInIndexCallback, $object);
        }
        return $this->getValue($object);
    }

    public function getValue($object)
    {
        $value = parent::getValue($object);
        return str_replace(",","",$value);
    }

    public function postProcessData($data)
    {
        $data = parent::postProcessData($data);
        return $this->asInteger ? $data * 100 : $data;
    }
}
