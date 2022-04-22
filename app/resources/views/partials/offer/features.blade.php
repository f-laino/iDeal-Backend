<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="Rent_monthly_rate"> {{ __('Canone mensile') }}</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">&euro;</div>
                </div>
                {!!  Form::text("monthly_rate",  !empty($offer) ? $offer->monthly_rate : old('monthly_rate'),
                ['class' => $errors->has('monthly_rate') ? 'form-control is-invalid' : 'form-control',
                'id' => 'Rent_monthly_rate',
                'placeholder' => 'Canone mensile']) !!}
            </div>
                @if ($errors->has('monthly_rate'))
                    <div class="help-block form-text with-errors form-control-feedback">
                        <strong>{{ $errors->first('monthly_rate') }}</strong></div>
                @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="First_deposit"> {{ __('Anticipo') }}</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">&euro;</div>
                </div>
                {!!  Form::text("deposit", !empty($offer) ? $offer->deposit : old('deposit'),
                ['class' => $errors->has('deposit') ? 'form-control is-invalid' : 'form-control',
                'id' => 'deposit',
                'placeholder' => 'Importo iniziale']) !!}
                @if ($errors->has('deposit'))
                    <div class="help-block form-text with-errors form-control-feedback">
                        <strong>{{ $errors->first('deposit') }}</strong></div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="Duration"> {{ __('Durata') }}</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">Mesi</div>
                </div>
                {!!  Form::text("duration",  !empty($offer) ? $offer->duration : old('duration'),
                ['class' => $errors->has('duration') ? 'form-control is-invalid' : 'form-control',
                'id' => 'Duration',
                'placeholder' => 'Durata espressa in mesi']) !!}
                @if ($errors->has('duration'))
                    <div class="help-block form-text with-errors form-control-feedback">
                        <strong>{{ $errors->first('duration') }}</strong></div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="TotalDistance"> {{ __('Distanza Anno') }}</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">KM</div>
                </div>
                {!!  Form::text("distance", !empty($totalDistance) ? $offer->distance : old('distance'),
                ['class' => $errors->has('distance') ? 'form-control is-invalid' : 'form-control',
                'id' => 'TotalDistance',
                'placeholder' => 'Distanza percorsa in totale']) !!}
            </div>
            @if ($errors->has('distance'))
                <div class="help-block form-text with-errors form-control-feedback">
                    <strong>{{ $errors->first('distance') }}</strong></div>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="delivery_time">{{ __('Tempi di consegna') }}</label>
            <div class="input-group">
                {!! Form::select('delivery_time', $deliveryTimes, !empty($offer) ? $delivery_time : old('delivery_time'),
                    [   'class' => $errors->has('delivery_time') ? 'form-control is-invalid' : 'form-control',
                        "id" => "delivery_time"
                        ]
                ) !!}
                @if ($errors->has('delivery_time'))
                    <div class="help-block form-text with-errors form-control-feedback">
                        <strong>{{ $errors->first('delivery_time') }}</strong></div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label></label>
            <div>
            <label for="fast_delivery">
                {{ Form::checkbox('fast_delivery', '1',
                    $fastDelivery ?? false,
                    ['class' => 'form-control', 'id' => 'fast_delivery']
                    )
                }}
                {{__('Consegna veloce')}}
            </label>
        </div>
        </div>
    </div>
</div>

