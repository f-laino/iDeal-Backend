<div class="row">
    <div class="col-md-12">
        <table class="table">
            <thead>
            <tr>
                <th></th>
                <th class="text-center" style="font-size: .9rem;">
                    Commissione Intermediari
                </th>
                <th class="text-center" style="font-size: .9rem;">
                    Commissione Capo Gruppo
                </th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($segments as $segment)
            <tr>
                <td rowspan="2" class="text-center">
                    Segmento <strong>{{ $segment }}</strong>
                </td>
                <td>
                    {!!  Form::text("$segment&agent&monthly_rate", !empty( $pattern["$segment&agent&monthly_rate"] ) ? $pattern["$segment&agent&monthly_rate"] : old("$segment&agent&monthly_rate"), ['class' => $errors->has("$segment&agent&monthly_rate") ? 'form-control is-invalid' : 'form-control', 'placeholder'=> '1.00']) !!}
                </td>
                <td>
                    {!!  Form::text("$segment&leader&monthly_rate",  !empty( $pattern["$segment&leader&monthly_rate"] ) ? $pattern["$segment&leader&monthly_rate"] : old("$segment&leader&monthly_rate"), ['class' => $errors->has("$segment&leader&monthly_rate") ? 'form-control is-invalid' : 'form-control', 'placeholder'=> '1.00']) !!}
                </td>
                <td>
                    Prezzo <strong>Intermediari</strong>
                </td>
            </tr>
            <tr>
                <td>
                    {!!  Form::text("$segment&agent&web_monthly_rate",  !empty( $pattern["$segment&agent&web_monthly_rate"] ) ? $pattern["$segment&agent&web_monthly_rate"] : old("$segment&agent&web_monthly_rate"), ['class' => $errors->has("$segment&agent&web_monthly_rate") ? 'form-control is-invalid' : 'form-control', 'placeholder'=> '1.00']) !!}
                </td>
                <td>
                    {!!  Form::text("$segment&leader&web_monthly_rate",  !empty( $pattern["$segment&leader&web_monthly_rate"] ) ? $pattern["$segment&leader&web_monthly_rate"] : old("$segment&leader&web_monthly_rate"), ['class' => $errors->has("$segment&leader&web_monthly_rate") ? 'form-control is-invalid' : 'form-control', 'placeholder'=> '1.00']) !!}
                </td>
                <td>
                    Prezzo <strong>Web</strong>
                </td>
            </tr>

        @endforeach

            </tbody>
        </table>
    </div>
</div>