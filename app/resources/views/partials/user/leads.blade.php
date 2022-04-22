<div class="element-wrapper" style="margin-top: 50px">
    <h6 class="element-header">
        Elenco Richieste
    </h6>
    <div class="element-box-tp">
        @foreach( $leads as $lead )
            <div class="profile-tile">
                @if(!empty($lead->offer()))
                <a class="profile-tile-box" href="{!! route("caroffers.edit", [$lead->offer->id])  !!}" target="_blank">
                    <div class="pt-avatar-w">
                        <img alt="" src="">
                    </div>
                    <div class="pt-user-name">
                        <span> CarBrand</span><br>
                        <span class="smaller lighter">CarName</span>
                    </div>
                </a>
                @endif
                <div class="profile-tile-meta">
                    <ul>
                        <li>
                            Numero:<strong> #{{ $lead->id }}</strong>
                        </li>
                        <li>
                            Data:<strong>{{ $lead->humanDate }}</strong>
                        </li>
                        <li>
                            Telefono:<strong> {{ $lead->phone }} </strong>
                        </li>
                        <li>
                            CAP :<strong>{{ $lead->address }}</strong>
                        </li>
                        <li>
                            C.F. :<strong>{{ $lead->fiscal_code }}</strong>
                        </li>
                        <li>
                            P.IVA :<strong>{{ $lead->vat_number }}</strong>
                        </li>
                    </ul>
                    <div class="pt-btn">
                        <a class="btn btn-success btn-sm" href="{!! route("deals.show", [$lead->id])  !!}" target="_blank">Visualizza</a>
                        <a class="btn btn-secondary btn-sm"
                           href="https://daicar.pipedrive.com/deal/{{$lead->crm_id}}"
                           target="_blank">Visualizza su Pipedrive</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>