@extends('layouts.main', [
        'title' => "Nuova lista documenti per " . $brokerLabel,
         "breadcrumbs" => ["Lista documenti" => "document-list.index", "Modifica" => "document-list.edit"],
         "breadcrumbsParams"=>[ "Modifica" => $brokerLabel ]
         ])

@section('app-content')

    <div class="element-wrapper">
        <div class="element-box">
            <div class="element-info">
                <div class="element-info-text">
                    <h5 class="element-inner-header">
                        {{ $brokerLabel }}
                    </h5>
                </div>
            </div>
            {{ Form::model($brokerDocumentList ,['route' => ['document-list.update', $broker], 'method' => 'PATCH', 'id' => "document-list-update"]) }}
            <div class="row">
                <div class="col-md-12">
                    <div class="os-tabs-w">
                        <div class="os-tabs-controls">
                            <ul class="nav nav-tabs">
                                @foreach ($contractualCategories as $category)
                                    <li class="nav-item">
                                        <a class="nav-link @if ($loop->first) active show @endif" data-toggle="tab" href="#category{{ $category->id }}">
                                            {{ $category['description'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="tab-content">
                            @foreach ($contractualCategories as $category)
                                <div class="tab-pane @if ($loop->first) active show @endif" id="category{{ $category->id }}">
                                    @foreach ($brokerDocuments[$category->id] as $brokerDocument)
                                        <div class="row documents-list-container-row">
                                            <div class="col-md-4">
                                                <label for="{{ $category->id }}-{{ $brokerDocument->id }}">
                                                    {{ Form::hidden('enabled[' . $category->id . '][' . $brokerDocument->id . ']', '0') }}
                                                    {{ Form::checkbox('enabled[' . $category->id . '][' . $brokerDocument->id . ']', '1',
                                                        $brokerDocument->enabled,
                                                        ['class' => 'form-control', 'id' => $category->id . '-' . $brokerDocument->id]) }}
                                                    {{ $brokerDocument['title'] }}
                                                </label>
                                            </div>
                                            <div class="col-md-4">
                                                {!! Form::text('titles[' . $category->id . '][' . $brokerDocument->id . ']',
                                                    $brokerDocument->custom_title,
                                                    [
                                                        'readonly' => !$brokerDocument->enabled,
                                                        'class' => 'form-control',
                                                        'id' => $category->id . '-' . $brokerDocument->id . 'title',
                                                        'placeholder' => 'Titolo'
                                                    ]) !!}
                                            </div>
                                            <div class="col-md-4">
                                                {!! Form::text('links[' . $category->id . '][' . $brokerDocument->id . ']',
                                                    $brokerDocument->custom_link,
                                                    [
                                                        'readonly' => !$brokerDocument->enabled,
                                                        'class' => 'form-control',
                                                        'id' => $category->id . '-' . $brokerDocument->id . 'link',
                                                        'placeholder' => 'Link'
                                                    ]) !!}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    {!! Form::submit('Aggiorna', ['class' => 'btn btn-primary pull-right']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.documents-list-container-row').on('change', 'input[type=checkbox]', function() {
            var $this = $(this);
            $this.closest('.row').find('input[type=text]').prop('readonly', !$this.is(':checked'));
        });
    });
    </script>
@endpush