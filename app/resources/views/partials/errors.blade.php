@if (!empty($errors) && $errors->has('custom_error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <ul class="app-errors">
                <li>{{ $errors->first('custom_error') }}</li>
            </ul>
        </div>
    @endif
