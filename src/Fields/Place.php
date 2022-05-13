<?php
/**
 * If you are going to use this field you must include the JS before using it.
 * Thrust::placesJs('TheGoogleApiKey')
 */

namespace BadChoice\Thrust\Fields;

class Place extends Text
{
    protected $type      = '';
    protected $countries = [];

    protected $relatedFields = [
        'address'       => 'address',
        'city'          => 'city',
        'postalCode'    => 'postalCode',
        'state'         => 'state',
        'country'       => 'country',
    ];

    /**
     * @param $type city, country, address, busStop, trainStation, townhall, airport
     * @return $this
     */
    public function type($type)
    {
        $this->type = $type;
        return $this;
    }

    public function countries($countries)
    {
        $this->countries = $countries;
    }

    public function city($cityField)
    {
        $this->relatedFields['city'] = $cityField;
        return $this;
    }

    public function state($stateField)
    {
        $this->relatedFields['state'] = $stateField;
        return $this;
    }

    public function postalCode($postalCode)
    {
        $this->relatedFields['postalCode'] = $postalCode;
        return $this;
    }

    public function country($country)
    {
        $this->relatedFields['country'] = $country;
        return $this;
    }

    public function displayInEdit($object, $inline = false)
    {
        return view('thrust::fields.place', [
            'field'             => $this->field,
            'title'             => $this->getTitle(),
            'value'             => $this->getValue($object),
            'type'              => $this->type,
            'relatedFields'     => $this->relatedFields,
            'validationRules'   => $this->getHtmlValidation($object, $this->getFieldType()),
        ])->render();
    }

    public static function javascript(string $key): string
    {
        return "<script type=\"text/javascript\" src=\"https://maps.googleapis.com/maps/api/js?key={$key}&libraries=places\"></script>";
    }
}
