<div id="childErrorArea"></div>
<div class="row">
    <div class="col">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button"><span aria-hidden="true"> ×</span></button>
            <strong>Attenzione! </strong>
            I valori delle distanze devono essere inseriti in ordine crescente all'interno delle colonne, in caso contrario tutti i dati della multiofferta potrebbero risultare non veritieri.<br/>
            <strong>Esempio corretto: 5,6,7</strong><br/>
            <strong>Esempio errato 6,5,7</strong>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="form-group">
            <label>Importa da Excel</label>
            <input class="form-control" id="excelPasteBox" placeholder="Incolla qui" type="text" value="">
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <table  class="table table-editable table-striped table-lightfont" style="cursor: pointer">
            <thead>
            <tr>
                <th class="text-center" style="border-bottom: unset">
                    <div class="input-group" style="border: none !important">
                        <div class="input-group-append">
                            <div class="input-group-text" style="font-size: 12px"> Anticipo</div>
                        </div>
                    </div>
                </th>
                <th class="text-center" style="border-bottom: unset">
                    <div class="input-group" style="border: none !important">
                        {!!  Form::text("first_distance", !empty($childsDistance[0]) ? $childsDistance[0] : 10000, ['class' =>'form-control', 'id' => 'first_distance', 'style' => 'padding:5px']) !!}
                        <div class="input-group-append">
                            <div class="input-group-text" style="font-size: 12px;padding: 5px;">KM</div>
                        </div>
                    </div>
                </th>
                <th class="text-center" style="border-bottom: unset">
                    <div class="input-group" style="border: none !important">
                        {!!  Form::text("second_distance", !empty($childsDistance[1]) ? $childsDistance[1] : 15000, ['class' =>'form-control', 'id' => 'second_distance', 'style' => 'padding:5px']) !!}
                        <div class="input-group-append">
                            <div class="input-group-text" style="font-size: 12px;padding: 5px;">KM</div>
                        </div>
                    </div>
                </th>
                <th class="text-center" style="border-bottom: unset">
                    <div class="input-group" style="border: none !important">
                        {!!  Form::text("third_distance", !empty($childsDistance[2]) ? $childsDistance[2] : 20000, ['class' =>'form-control', 'id' => 'third_distance', 'style' => 'padding:5px']) !!}
                        <div class="input-group-append">
                            <div class="input-group-text" style="font-size: 12px;padding: 5px;">KM</div>
                        </div>
                    </div>
                </th>
                <th class="text-center"style="border-bottom: unset">
                    <div class="input-group" style="border: none !important">
                        <div class="input-group-append">
                            <div class="input-group-text" style="font-size: 12px;">Durata</div>
                        </div>
                    </div>

                </th>
            </tr>
            </thead>
        </table>
    </div>
</div>
<div class="row" id="output">
    <div class="col">
        <table id="excelDataTable" class="table table-editable table-striped table-lightfont" style="cursor: pointer"></table>
    </div>
</div>
<div class="row" style="margin-top: 1rem;">
    <div class="col text-right">
        <button type="button" class="btn btn-primary" onclick="addChildOffers({{$offer->id}})">
            Salva
        </button>
        @if(!empty($childOffers))
            <button type="button" class="btn btn-info" onclick="showChangeMainModal({{$offer->id}})" style="color: white">
                Seleziona Main
            </button>
            <a class="btn btn-danger pull-right"
               style="margin-left:10px;"
               title="Elimina le offerte correlate al quest'offerta base"
               onclick="return confirm('Eliminando le variazioni l\'utente non avra piu la possibilita di variare i parametri dell\'offerta. Sei sicuro di voler procedere con l\'eliminazione di tutte le variazioni?')"
               href="{{route('offer.deleteChild', ['id'=>$offer->id])}}"
            >
                Elimina variazioni
            </a>
        @endif
    </div>
</div>
@includeWhen(!empty($childOffers), 'partials.offer.setMain')
@push('stylesheets')
    <style>
        hr {
            width: 90%;
            margin: 10px;
        }

        th > div {
            border: thin #d3d3d3 dashed;
            padding: 2px;
        }

        td > div {
            padding: 2px;
        }

        .ignored {
            background-color: #d3d3d3;
            color: grey;
        }

        th.ignored > div:before {
            content: "Column Ignored";
        }
        .dynatable-head{
            display: none;
        }
        #excelDataTable td{
            text-align: center!important;
        }
    </style>
@endpush

@push('scripts')
    <script type="text/javascript">
        var _isDirty = false;
        $(document).ready(function () {
            $(document).on('keypress', 'input#excelPasteBox', function (e) {
                if (e.ctrlKey !== true && e.key != 'v') {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
            $(document).on('paste', 'input#excelPasteBox', function (e) {
                e.preventDefault();
                var cb;
                var clipText = '';
                if (window.clipboardData && window.clipboardData.getData) {
                    cb = window.clipboardData;
                    clipText = cb.getData('Text');
                } else if (e.clipboardData && e.clipboardData.getData) {
                    cb = e.clipboardData;
                    clipText = cb.getData('text/plain');
                } else {
                    cb = e.originalEvent.clipboardData;
                    clipText = cb.getData('text/plain');
                }
                var clipRows = clipText.split('\n');
                for (i = 0; i < clipRows.length; i++) {
                    clipRows[i] = clipRows[i].split('\t');
                }
                var jsonObj = [];
                for (i = 0; i < clipRows.length - 1; i++) {
                    var item = {};
                    for (j = 0; j < clipRows[i].length; j++) {
                        if (clipRows[i][j] != '\r') {
                            if (clipRows[i][j].length !== 0) {
                                //Round dell'input here
                                var val = parseFloat(clipRows[i][j]);
                                item[j] = Math.round(val);
                            }
                        }
                    }
                    jsonObj.push(item);
                }
                renderTable(jsonObj);
            });
            jQuery.fn.pop = [].pop;
            jQuery.fn.shift = [].shift;
            $(document).on('click', 'button#exportJsonData', function () {
                var $rows = $('table#excelDataTable').find('tr:not(:hidden)');
                var headers = [];
                var data = [];
                $($rows.shift()).find('th:not(:empty):not([data-attr-ignore])').each(function () {
                    headers.push($(this).text().toLowerCase());
                });
                $rows.each(function () {
                    var $td = $(this).find('td:not([data-attr-ignore])');
                    var h = {};
                    headers.forEach(function (header, i) {
                        h[header] = $td.eq(i).text();
                    });
                    data.push(h);
                });
                var jsonString = JSON.stringify(data, null, 2);
                $('input#jsonDataDump').val(jsonString);
            });
        });

        function addChildOffers(offer) {
            _isDirty = false;
            var $rows = $('table#excelDataTable').find('tr:not(:hidden)');
            var headers = [];
            var data = [];
            $($rows.shift()).find('th:not(:empty):not([data-attr-ignore])').each(function () {
                headers.push($(this).text().toLowerCase());
            });
            $rows.each(function () {
                var $td = $(this).find('td:not([data-attr-ignore])');
                var h = {};
                headers.forEach(function (header, i) {
                    h[header] = $td.eq(i).text();
                });
                data.push(h);
            });
            var jsonString = JSON.stringify(data, null, 2);

            $('input#jsonDataDump').val(jsonString);
            $.post("{{route('offer.addChilds')}}", {
                "data": jsonString,
                "first_distance": $('#first_distance').val(),
                "second_distance": $('#second_distance').val(),
                "third_distance": $('#third_distance').val(),
                "offer": offer,
            }).done(function (data) {
                $("#childErrorArea").html('');
                if (data.status === true) {
                    window.location.reload(true);
                }
                else {
                    var content = ` <div class="alert alert-danger" role="alert"><strong>Caspita! </strong>${data.error}</div>`;
                    $("#childErrorArea").html(content);
                }
            });
        }

        function renderTable(jsonObj){

            var customCellWriter = function (column, record) {
                var html = column.attributeWriter(record),
                    td = '<td';
                if (column.hidden || column.textAlign) {
                    td += ' style="';
                    if (column.hidden) {
                        td += 'display: none;';
                    }
                    if (column.textAlign) {
                        td += 'text-align: ' + column.textAlign + ';';
                    }
                    td += '"';
                }
                return td + '><div>' + html + '<\/td>';
            };
            var makeElementEditable = function (element) {
                $('div', element).attr('contenteditable', true);
                $(element).focusout(function () {
                    $('div', element).attr('contenteditable', false);
                });
                $(element).keydown(function (e) {
                    if (e.which == 13) {
                        e.preventDefault();
                        $('div', element).attr('contenteditable', false);
                        $(document).focus();
                    }
                });
                $('div', element).on('paste', function (e) {
                    e.preventDefault();
                });
            };

            var tablePlaceHolder = document.getElementById('output');
            tablePlaceHolder.innerHTML = '';
            var table = document.createElement('table');
            table.id = 'excelDataTable';
            table.className = 'table';
            var header = table.createTHead();
            var row = header.insertRow(0);
            var keys = [];
            for (var i = 0; i < jsonObj.length; i++) {
                var obj = jsonObj[i];
                for (var j in obj) {
                    if ($.inArray(j, keys) == -1) {
                        keys.push(j);
                    }
                }
            }
            keys.forEach(function (value, index) {
                var headerCell = document.createElement('th');
                headerCell.innerHTML = '<div>' + value + '<\/div>';
                $(headerCell).click(function () {
                    makeElementEditable(this);
                });
                $(headerCell).keyup(function (e) {
                    var ignoredClass = 'ignored';
                    var ignoredAttr = 'data-attr-ignore';
                    var columnCells = $('td, th', table).filter(':nth-child(' + ($(this).index() + 1) + ')');
                    $(this).removeAttr(ignoredAttr);
                    $(columnCells).each(function () {
                        $(this).removeClass(ignoredClass);
                        $(this).removeAttr(ignoredAttr);
                    });
                    if ($(this).is(':empty') || $(this).text().trim() === '') {
                        $(this).attr(ignoredAttr, '');
                        $(columnCells).each(function () {
                            $(this).addClass(ignoredClass);
                            $(this).attr(ignoredAttr, '');
                        });
                    }
                });
                var cell = row.insertCell(index);
                cell.parentNode.insertBefore(headerCell, cell);
                cell.parentNode.removeChild(cell);
            });
            tablePlaceHolder.appendChild(table);
            var excelDynaTable = $('table#excelDataTable').dynatable({
                features: {
                    paginate: false,
                    search: false,
                    recordCount: false,
                    sort: false
                },
                dataset: {
                    records: jsonObj
                },
                writers: {
                    _cellWriter: customCellWriter
                }
            });
            $(document).on('click', 'table#excelDataTable td', function () {
                _isDirty = true;
                makeElementEditable(this);
            });
        }

        function showChildOffers() {
            $.ajax({
                type: 'get',
                url: "{{route('offer.showChilds', ['id'=>$offer->id])}}",
                success: function(data) {
                    renderTable(data.data);
                }
            });
        }

        function showChangeMainModal(offer) {
            $('#setMainModal').modal('toggle');
            // $('#child_offer_id').val(offer);
        }


        window.onbeforeunload = function() {
            if(_isDirty)
                return "Ci sono delle variazioni non salvate. Sei sicuro di vole chiudere la pagina?";
        }

    </script>
@endpush


