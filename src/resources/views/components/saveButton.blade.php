<button class="button-with-loading" @if($confirmationMessage) onclick="return confirm('{{$confirmationMessage}}')" @endif>
        <span class="loadingImage">
            <i class="fa fa-circle-o-notch fa-spin"></i>
        </span>
    {{ __("thrust::messages.save") }}
</button>