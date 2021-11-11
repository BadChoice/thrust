<br><br>
@include('thrust::fields.input', [
    'inline'          => false,
    'title'           => __('thrust::messages.amount'),
    'type'            => 'number',
    'field'           => 'amount',
    'value'           => 1,
    'validationRules' => 'required min=1',
    'attributes'      => null,
    'description'     => __('thrust::messages.amountDesc'),
])