@extends('layouts.main', [
        'title' => "Modifica documento",
         "breadcrumbs" => ["Elenco documenti" => "documents.index", "Modifica" => "documents.edit"],
         "breadcrumbsParams"=>[ "Modifica" => $document['id'] ]
         ])

@section('app-content')

    <div class="element-wrapper">
        <div class="element-box">
            <div class="element-info">
                <div class="element-info-text">
                    <h5 class="element-inner-header">
                        {{ $document['title'] }}
                    </h5>
                </div>
            </div>
            {{ Form::model($document ,['route' => ['documents.update', $document['id']], 'method' => 'PATCH', 'id' => "documents-update", 'files'=>true]) }}
            <div class="row">
                <div class="col-md-6">
                    <label for="title">{{ __('Titolo') }}</label>
                    {!!  Form::text("title", $document['title'], ['class' => $errors->has('title') ? 'form-control is-invalid' : 'form-control', 'id' => 'title', 'placeholder' => 'Titolo']) !!}
                    @if ($errors->has('title'))
                        <div class="help-block form-text with-errors form-control-feedback">
                            <strong>{{ $errors->first('title') }}</strong></div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label for="link">
                        @if ($document['link'])
                            <a href="{{ $document['link'] }}" title="{{ $document['link'] }}" target="_blank">
                                {{ __('Link') }} <i class="os-icon os-icon-ui-51"></i>
                            </a>
                        @else
                            {{ __('Link') }}
                        @endif
                    </label>
                    {!!  Form::file("link", ['accept' => '.pdf', 'class' => $errors->has('link') ? 'form-control-file is-invalid' : 'form-control-file', 'id' => 'link']) !!}
                    @if ($errors->has('link'))
                        <div class="help-block form-text with-errors form-control-feedback">
                            <strong>{{ $errors->first('link') }}</strong></div>
                    @endif
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