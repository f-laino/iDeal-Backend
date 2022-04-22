<div class="table-responsive">
    <table class="table table-padded">
        <thead>
        <tr>
            <th>
                IMG
            </th>
            <th>
                Path
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
        @foreach($images as $image)
            <tr>
                <td class="cell-with-media">
                    <img alt=" Logo"
                         src="{{ $image->path }}"
                         style="height: 25px;">
                </td>
                <td class="nowrap">
                    <a
                            href="{{ $image->path }}"
                            target="_blank"
                            data-toggle="tooltip" title="{{ $image->path }}">
                        {{ str_limit($image->path, 20 )}}
                    </a>
                </td>
                <td class="nowrap">
                    <span class="{{ $image->type == "MAIN" ? 'badge badge-success': '' }} ">{{ $image->type }}</span>
                </td>
                <td class="text-right bolder nowrap">
                    <a class="btn btn-outline-info btn-sm"
                       href="{{ route("image.edit", [$image->id]) }}"
                       data-toggle="tooltip" title="Modifica i dati relativi all'immagine" target="_blank">
                        <i class="os-icon os-icon-edit-32"></i> Modifica
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <a class="btn btn-info text-center" style="width: 100%"
       href="{{ route("car.image.create", [$car->id]) }}"
       data-toggle="tooltip" title="Aggiungi una nuova immagine all'offerta" target="_blank">
        <i class="os-icon os-icon-edit-32"></i> {{__('Aggiungi immagine')}}
    </a>
</div>
