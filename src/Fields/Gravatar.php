<?php

namespace BadChoice\Thrust\Fields;

class Gravatar extends Field
{
    public $field      = 'email';
    public $showInEdit = false;
    protected $default = 'mm';

    public function displayInIndex($object)
    {
        return $this->getImageTag(data_get($object, $this->field));
    }

    public function displayInEdit($object, $inline = false)
    {
        return '';
    }

    public function withDefault($default)
    {
        $this->default = $default;
        return $this;
    }

    public function getUrl($email, $size = 30)
    {
        $email       = md5(strtolower(trim($email)));
        $gravatarURL = 'https://www.gravatar.com/avatar/';
        $gravatarURL .= $email.'?s='.$size."&d={$this->default}";
        return $gravatarURL;
    }

    public function getImageTag($email, $size = 30)
    {
        $gravatarURL = $this->getUrl($email, $size);
        return '<img id = '.$email.''.$size.' class="gravatar" src="'.$gravatarURL.'" width="'.$size.'">';
    }
}
