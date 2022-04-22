<div class="form-group">
    <label for="Short_Description"> {{ __('Descrizione Footer') }}</label>
    {!!  Form::textarea("short_description",  !empty($description) ? $description->value : old('short_description'),
    ['class' => $errors->has('short_description') ? 'form-control is-invalid' : 'form-control',
    'id' => 'Short_Description',
    'rows' => 1,
    'style' => 'resize:none',
    'placeholder' => 'Descrizione']) !!}
    @if ($errors->has('short_description'))
        <div class="help-block form-text with-errors form-control-feedback">
            <strong>{{ $errors->first('short_description') }}</strong></div>
    @endif
</div>

<div class="form-group">
    <label for="description"> {{ __('Descrizione Overlay') }}</label>
    {!!  Form::textarea("description",  !empty($description) ? $description->description : old('description'),
    ['class' => $errors->has('description') ? 'form-control is-invalid' : 'form-control',
    'id' => 'description',
    'rows' => 3,
    'style' => 'resize:none',
    'placeholder' => 'Descrizione']) !!}
    @if ($errors->has('description'))
        <div class="help-block form-text with-errors form-control-feedback">
            <strong>{{ $errors->first('description') }}</strong></div>
    @endif
</div>