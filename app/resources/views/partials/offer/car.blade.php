@php($isCustomCar = !empty($car) ? $car->is_custom : 0 )
{{ Form::hidden('custom-car-enabled', $isCustomCar, ['id' => 'custom-car-enabled']) }}
<div class="row mt-4">
    <div class="col" style="border-bottom: 1px solid rgba(0, 0, 0, 0.05);margin-bottom: 10px;padding-bottom: 5px;">
        <strong>{{__('Veicolo Custom')}}</strong>
        <label class="btn-sm">
            <div class="os-toggler-w {{ $isCustomCar ? "on" : "" }}"
                 style="margin-bottom: -5px;"
                 onclick="return handleOnCustomCarChange(this);">
                <div class="os-toggler-i" style="background-color: #E1E6F2;">
                    <div class="os-toggler-pill"></div>
                </div>
            </div>
        </label>
    </div>
</div>
<div class="row" id="webserviceCars"
     @if($isCustomCar)
        style="display: none;"
    @endif
>
    <div class="col-md-3">
        <label for="Brand_id">{{ __('Marca') }}</label>
        {{ Form::select('brand_id', $brands, !empty($car)? $car->brand_id : old('brand_id'),
            [   'class' => $errors->has('brand_id') ? 'form-control is-invalid selectpicker' : 'form-control selectpicker',
                "id" => "Brand_id",
                "data-live-search" => "true",
                "data-dropup-auto"=> "true",
                "data-size"=> "5",
                "data-max-options" => "1",
                ]
         ) }}
        @if ($errors->has('brand_id'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('brand_id') }}</strong></div>
        @endif
    </div>
    <div class="col-md-4">
        <label for="Carmodel_id">{{ __('Modello') }}</label>
        {{ Form::select('carmodel', !empty($models) ? $models : [], !empty($car) ? $car->cod_gamma_mod : old('carmodel'),
                [
                'class' => $errors->has('carmodel_id') ? 'form-control is-invalid selectpicker' : 'form-control selectpicker',
                "id" => "Carmodel_id",
                "data-live-search" => "true",
                "data-dropup-auto"=> "true",
                "data-size"=> "7",
                "data-max-options" => "1"
                ]
              )
        }}
        @if ($errors->has('carmodel_id'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('carmodel_id') }}</strong></div>
        @endif
    </div>
    <div class="col-md-5">
        <label for="Carversion_id">
            @if(!empty($car))
                <a href="{{route('car.edit', ['car' => $car->id])}}"
                   data-toggle="tooltip" title="Visualizza dettaglio allestimento"
                   target="_blank">
                    {{ __('Visualizza') }}
                </a>
            @else
                {{ __('Allestimento') }}
            @endif

        </label>
        {{ Form::select('carversion', !empty($versions) ? $versions : [],  !empty($car) ? "$car->codice_motornet-$car->codice_eurotax" : old('carversion'),
                [
                'class' => $errors->has('carversion') ? 'form-control is-invalid selectpicker' : 'form-control selectpicker',
                "id" => "Carversion_id",
                "data-live-search" => "true",
                "data-dropup-auto"=> "true",
                "data-size"=> "7",
                "data-max-options" => "1"
                ]
              )
        }}
        @if ($errors->has('carversion'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('carversion') }}</strong></div>
        @endif
    </div>
</div>

<div class="row" id="customCars"
@if(!$isCustomCar)
    style="display: none"
@endif
>
    <div class="col-md-12">
        <label for="custom_car">
            {{ __('Seleziona allestimento') }}
        </label>
        <div class="float-right">
            <span id="show-car-link" @if(empty($car) || !isset($customCars[$car->id])) style="display: none" @endif>
                <a href="{{route('car.edit', ['car' => (!empty($car->id) && isset($customCars[$car->id])) ? $car->id : '0'])}}"
                    data-toggle="tooltip" title="Visualizza dettaglio allestimento"
                    target="_blank">
                     {{ __('Visualizza') }}
                 </a>
                 |
            </span>
            <a  href="{{route('car.create')}}" target="_blank"
                data-toggle="tooltip" title="Aggiungi un nuovo allestimento">
                {{ __('Aggiungi') }}
            </a>
        </div>
        {{ Form::select('custom_car', !empty($customCars) ? $customCars : [], !empty($car) ? $car->id : old('custom_car'),
             [
             'class' => $errors->has('custom_car') ? 'form-control is-invalid selectpicker' : 'form-control selectpicker',
             "id" => "custom_car",
             "data-live-search" => "true",
             "data-dropup-auto"=> "true",
             "data-size"=> "7",
             "data-max-options" => "1",
             "onchange" => "return handleOnCarChange(this);"
             ]
           )
     }}
        @if ($errors->has('custom_car'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('custom_car') }}</strong></div>
        @endif
    </div>
</div>

@if(isset($offer))
<div class="row">
    <div class="col-md-12 mt-2">
        <label for="car_color">
            {{ __('Seleziona colore') }}
        </label>
        {{ Form::select('car_color', !empty($availableColors) ? $availableColors : [], !empty($carColor) ? $carColor->value : old('car_color'),
            [
            'class' => $errors->has('car_color') ? 'form-control is-invalid selectpicker' : 'form-control selectpicker',
            "id" => "car_color",
            "data-live-search" => "true",
            "data-dropup-auto"=> "true",
            "data-size"=> "4",
            "data-max-options" => "1"
            ]
          )
    }}
    </div>
</div>
@endif

@push('scripts')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#Brand_id').selectpicker();
            $('#Carmodel_id').selectpicker();
            $('#Carversion_id').selectpicker();


            $('#Brand_id').on('change', function () {
             loadModels($(this).val());
            });
            $('#Carmodel_id').on('change', function () {
              loadVersions( $(this).val() );
            });

            function loadModels( brand ) {
                $.ajax({
                    dataType: "json",
                    type: 'post',
                    url: "{{route('offer.models')}}",
                    data: {
                        'brand' : brand
                    },
                    success: (response) => {

                        $('#Carmodel_id').html('');
                        let carModelTpl = '<option value=""></option>';
                        response.map((car) => {
                            carModelTpl += `<option value="${car.cod_gamma_mod}">${car.desc_gamma_mod.toUpperCase()}</option>`
                        });

                        $('#Carmodel_id').html(carModelTpl);
                        $('#Carmodel_id').selectpicker('refresh');
                    }
                });
            }

            function loadVersions( code ){
                $.ajax({
                    dataType: "json",
                    type: 'post',
                    url: "{{route('offer.versions')}}",
                    data: {
                        'cod_gamma_mod' : code
                    },
                    success: (response) => {
                        console.log("Versions", response);

                        $('#Carversion_id').html('');
                        let carVersionTpl = '<option value=""></option>';
                        response.map((car) => {
                            carVersionTpl += `<option value="${car.CodiceMotornet}-${car.CodiceEurotax}">${car.Nome.toUpperCase()}</option>`
                        });

                        $('#Carversion_id').html(carVersionTpl);
                        $('#Carversion_id').selectpicker('refresh');
                    }
                });
            }

        });

        function handleOnCustomCarChange(event) {
            var status = $('#custom-car-enabled').val();
            if(status == 1){
                $('#customCars').hide();
                $('#webserviceCars').show();
                $(event).removeClass("on");
                $('#custom-car-enabled').val(0);
            } else {
                $('#webserviceCars').hide();
                $('#customCars').show();
                $(event).addClass("on");
                $('#custom-car-enabled').val(1);
            }
        }

        function handleOnCarChange(event) {
            var carId = $(event).val();
            var showCarLink = $('#show-car-link');
            var route = '{{route('car.edit', ['car' => '0000'])}}';
            showCarLink.find('a').attr('href', route.replace('0000', carId));
            showCarLink.show();
        }
    </script>
@endpush
