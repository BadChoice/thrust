<?php

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
}
