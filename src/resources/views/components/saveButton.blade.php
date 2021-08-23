<button class="button-with-loading" @if($updateConfirmationMessage) onclick="return confirm(`{{$updateConfirmationMessage}}`)" @endif>
        <span class="loadingImage">
            <i class="fa fa-circle-o-notch fa-spin"></i>
        </span>
    {{ __("thrust::messages.save") }}
</button>