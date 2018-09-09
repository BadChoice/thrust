<?php

namespace BadChoice\Thrust\Html;

class Validation
{
    protected $rules;
    protected $type;

    public function __construct($rules, $type = "text")
    {
        $this->rules    = collect(explode("|", $rules));
        $this->type     = $type;
    }

    public static function make($rules, $type = 'text')
    {
        return new self($rules, $type);
    }

    public function generate()
    {
        return $this->rules->reduce(function ($carry, $rule) {
            return $carry . $this->appendRule($carry, $rule);
        });
    }

    public function appendRule(&$output, $rule)
    {
        $params = collect(explode(":", $rule));
        $rule   = $params->first();
        if ($rule == 'required') {
            $this->appendRuleRequired($output);
        } elseif ($rule == 'min') {
            $this->appendRuleMin($output, $params[1]);
        } elseif ($rule == 'max') {
            $this->appendRuleMax($output, $params[1]);
        } elseif ($rule == 'digits') {
            $this->appendRuleDigits($output, $params[1]);
        } elseif ($rule == 'email') {
            $this->appendRuleEmail($output);
        } elseif ($rule == 'ip') {
            $this->appendRuleIp($output);
        }
    }

    public function appendRuleRequired(&$output)
    {
        $output .= " required ";
    }

    public function appendRuleMin(&$output, $min)
    {
        if ($this->type == "number") {
            return $this->appendRuleNumberMin($output, $min);
        }
        $output .= " pattern='.{".$min.",}' title='Min ".$min." or more characters' ";
    }

    public function appendRuleMax(&$output, $max)
    {
        if ($this->type == "number") {
            return $this->appendRuleNumberMax($output, $max);
        }
        $output .= " pattern='.{0,".$max."}' title='Max ".$max." characters' ";
    }

    public function appendRuleDigits(&$output, $digits)
    {
        $output .= "pattern='.{".$digits.",".$digits."}' title='Digits ".$digits." characters' ";
    }

    public function appendRuleEmail(&$output)
    {
        if ($this->type == "email"){
            return;
        }
        $output .= " pattern='/^[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,4}$/' ";
    }

    public function appendRuleIp(&$output)
    {
        $output .= " pattern='((^|\\.)((25[0-5])|(2[0-4]\\d)|(1\\d\\d)|([1-9]?\\d))){4}$' ";
    }

    public function appendRuleNumberMin(&$output, $min)
    {
        $output .= " min='{$min}' ";
    }

    public function appendRuleNumberMax(&$output, $max)
    {
        $output .= " max='{$max}' ";
    }
}

/*

function validateForm(rules){
    jQuery.each(rules, function(key, val) {
        var rules = val.split("|");
        jQuery.each(rules, function(i, rule){
            addRule(key,rule);
        });
    });
}

function addRule(field, rule){
    var params = rule.split(":");
    rule = params[0];
    //console.log("Add rule " + rule + " to field " + field);
    if      (rule == 'required') addRuleRequired(field);
    else if (rule == 'email')    addRuleEmail   (field);
    else if (rule == 'numeric')  addRuleNumeric (field);
    else if (rule == 'numeric')  addRuleInteger (field);
    else if (rule == 'min')      addRuleMin     (field, params[1]);
    else if (rule == 'url')      addRuleURL     (field);
    else if (rule == 'ip')       addRuleIP      (field);
    else if (rule == 'domain')   addRuleDomain  (field);
}

function addRuleRequired(field){
    $("#"+field).prop('required',true);
}

function addRuleEmail(field){
    $("#"+field).attr('type','email');
}

function addRuleNumeric(field){
    $("#"+field).attr('type','number')
                .attr('step','any');
}

function addRuleInteger(field){
    $("#"+field).attr('type','number');
}

function addRuleMin(field,min){
    $("#"+field).attr('pattern','.{' + min + ',}')
                .attr('title', min + ' Or more characters');
}

function addRuleURL(field){
    $("#"+field).attr('pattern','https?://.+')
                .attr('title', 'Needs to be a webpage');
}

function addRuleIP(field){
    $("#"+field).attr('pattern','((^|\\.)((25[0-5])|(2[0-4]\\d)|(1\\d\\d)|([1-9]?\\d))){4}$')
                .attr('title', 'Needs to be an IP');
}

function addRuleDomain($field){
    $("#"+field).attr('pattern','^([a-zA-Z0-9]([a-zA-Z0-9\\-]{0,61}[a-zA-Z0-9])?\\.)+[a-zA-Z]{2,6}$')
                .attr('title', 'Needs to be an IP');
}

 */