@extends('layouts.main', ['title' => "Elenco Categorie Contrattuali"])

@section('app-content')
    <div class="element-box-tp">
        <div class="table-responsive">
            <table class="table table-padded">
                <thead>
                <tr>
                    <th>
                        #
                    </th>
                    <th>
                        Slug
                    </th>
                    <th>
                        Descrizione
                    </th>
                    <th>
                        Tipologia
                    </th>

                    <th>
                        Azioni
                    </th>

                </tr>
                </thead>
                <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td class="nowrap">
                            <span>{{ $category->id }} </span>
                        </td>
                        <td class="nowrap">
                            <span>{{ $category->code }}</span>
                        </td>
                        <td class="nowrap">
                            <span>{{ $category->description }}</span>
                        </td>
                        <td class="nowrap">
                            @if ($category->for_private)
                                <span>privato</span>
                            @endif
                            @if ($category->for_business)
                                <span>business</span>
                            @endif
                        </td>

                        <td class="text-right">
                            <a class="btn btn-outline-info btn-sm"
                               href="{!! route('category.edit', [$category->id]) !!}" data-toggle="tooltip"
                               title="Modifica Categoria">
                                <i class="os-icon os-icon-edit-32"></i> Modifica</a>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {!! $categories; !!}
@endsection

