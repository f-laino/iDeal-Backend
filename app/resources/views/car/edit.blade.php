@extends('layouts.main', [
         "breadcrumbs" => ["Elenco Allestimenti Auto" => "car.index", "Modifica" => "car.edit"],
         "breadcrumbsParams"=>[ "Modifica" => $car->id ]
         ])


@section('app-content')
    <div class="element-wrapper">
        {{ Form::model($car ,['route' => ['car.update', $car->id], 'method' => 'PATCH', "id" => "car-update"]) }}
        <div class="row">
            <div class="col-md-8">
                <div class="element-box">
                    <div class="element-info">
                        <div class="element-info-with-icon">
                            <div class="element-info-text">
                                <h5 class="element-inner-header">
                                    <img alt="{{ $car->brand->name }} Logo"
                                         src="{{ $car->brand->logo }}"
                                         style="height: 20px;">
                                    <span>{{ $car->brand->name }} {{ $car->descrizione_serie_gamma }}</span>
                                </h5>
                                <div class="form-desc"
                                     style="margin-bottom: unset!important; border-bottom: unset; padding-bottom:unset;">
                                    Codice Motornet: <code>{{ $car->codice_motornet }}</code>
                                    Codice Eurotax: <code>{{ $car->codice_eurotax }}</code>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('partials.car.features')
                </div>
            </div>
            <div class="col-md-4">
                <div class="element-box">
                    <h5 class="form-header">
                        {{ __('Azioni') }}
                        <div class="form-desc"></div>
                    </h5>
                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::submit('Aggiorna allestimento', ['class' => 'btn btn-primary pull-right', 'style'=> 'width:100%']) !!}
                            {!! Form::close() !!}

                            {!! Form::open(['route' => ['cars.accessories.updateFromSource', $car->id], 'method' => 'post', "id" => "car-accessories-update"]) !!}
                            {!! Form::submit('Aggiorna accessori', [
                               'class' => 'btn btn-warning pull-right',
                               "onclick" => "return confirm('Sei sicuro di voler aggiornare gli accessori per questo allestimento?')",
                               "data-toggle" => "tooltip",
                               "title" => "Aggiorna accessori allestimento",
                               'style'=> 'margin-top: 10px; width:100%'
                           ]) !!}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <div class="element-box">
        <div class="os-tabs-w">
            <div class="os-tabs-controls">
                <ul class="nav nav-tabs bigger" style="font-size: 1rem!important;">
                    <li class="nav-item">
                        <a class="nav-link active show" data-toggle="tab" href="#serie">
                            {{ __('Serie (' . count($equippedAccessories) . ')' ) }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#optional">
                            {{ __('Optional (' . count($optionalAccessories) . ')' ) }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#colors">
                            {{ __('Colori (' . count($colors) . ')' ) }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#packs">
                            {{ __('Pachetti (' . count($packs) . ')' ) }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#rent">
                            {{ __('Offerte') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#images">
                            {{ __('Immagini') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#raw-data">
                            {{ __('Eurotax') }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane active show" id="serie">
                    @include('partials.car.accessories', ['items' => $equippedAccessories])
                </div>
                <div class="tab-pane" id="optional">
                    @include('partials.car.accessories', ['items' => $optionalAccessories])
                </div>
                <div class="tab-pane" id="colors">
                    @include('partials.car.accessories', ['items' => $colors])
                </div>
                <div class="tab-pane" id="packs">
                    @include('partials.car.accessories', ['items' => $packs])
                </div>
                <div class="tab-pane" id="rent">
                    @if( !$offers->isEmpty() )
                        @include('partials.offer.list')
                    @else
                        Quest'automobile non &egrave; presente in alcuna <a href="{{route('offer.index')}}">Offerta
                            Noleggio</a>
                    @endif
                </div>

                <div class="tab-pane" id="images">
                    @include('partials.offer.images')
                </div>
                <div class="tab-pane" id="raw-data">
                    <div id="car-json"></div>
                </div>

            </div>
        </div>
    </div>
    </div>



@endsection

@push('scripts')
    <script type="text/javascript">
        $.fn.jJsonViewer = function (jjson, options) {
            return this.each(function () {
                var self = $(this);
                if (typeof jjson == 'string') {
                    self.data('jjson', jjson);
                } else if (typeof jjson == 'object') {
                    self.data('jjson', JSON.stringify(jjson))
                } else {
                    self.data('jjson', '');
                }
                new JJsonViewer(self, options);
            });
        };

        function JJsonViewer(self, options) {
            var json = $.parseJSON(self.data('jjson'));
            options = $.extend({}, this.defaults, options);
            var expanderClasses = getExpanderClasses(options.expanded);
            self.html('<ul class="jjson-container"></ul>');
            self.find('.jjson-container').append(json2html([json], expanderClasses));
        }

        function getExpanderClasses(expanded) {
            if (!expanded) return 'expanded collapsed hidden';
            return 'expanded';
        }

        function json2html(json, expanderClasses) {
            var html = '';
            for (var key in json) {
                if (!json.hasOwnProperty(key)) {
                    continue;
                }

                var value = json[key],
                    type = typeof json[key];

                html = html + createElement(key, value, type, expanderClasses);
            }
            return html;
        }

        function encode(value) {
            return $('<div/>').text(value).html();
        }

        function createElement(key, value, type, expanderClasses) {
            var klass = 'object',
                open = '{',
                close = '}';
            if ($.isArray(value)) {
                klass = 'array';
                open = '[';
                close = ']';
            }
            if (value === null) {
                return '<li><span class="key">"' + encode(key) + '": </span><span class="null">"' + encode(value) + '"</span></li>';
            }
            if (type == 'object') {
                var object = '<li><span class="' + expanderClasses + '"></span><span class="key">"' + encode(key) + '": </span> <span class="open">' + open + '</span> <ul class="' + klass + '">';
                object = object + json2html(value, expanderClasses);
                return object + '</ul><span class="close">' + close + '</span></li>';
            }
            if (type == 'number' || type == 'boolean') {
                return '<li><span class="key">"' + encode(key) + '": </span><span class="' + type + '">' + encode(value) + '</span></li>';
            }
            return '<li><span class="key">"' + encode(key) + '": </span><span class="' + type + '">"' + encode(value) + '"</span></li>';
        }

        $(document).on('click', '.jjson-container .expanded', function (event) {
            event.preventDefault();
            event.stopPropagation();
            var $self = $(this);
            $self.parent().find('>ul').slideUp(100, function () {
                $self.addClass('collapsed');
            });
        });

        $(document).on('click', '.jjson-container .expanded.collapsed', function (event) {
            event.preventDefault();
            event.stopPropagation();
            var $self = $(this);
            $self.removeClass('collapsed').parent().find('>ul').slideDown(100, function () {
                $self.removeClass('collapsed').removeClass('hidden');
            });
        });

        JJsonViewer.prototype.defaults = {
            expanded: true
        };

        var jjson = '{!! json_encode( $car->getAttributes())  !!} ';
        $("#car-json").jJsonViewer(jjson);
    </script>
@endpush





