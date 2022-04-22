<div class="element-wrapper" style="padding-bottom: unset!important;">
    <div class="element-box">
        <div class="element-info">
            <div class="element-info-with-icon">
                <div class="element-info-text">
                    <h5 class="element-inner-header">
                        Generatore Prezzi Intermediari
                    </h5>
                    <div class="form-desc"
                         style="margin-bottom: unset!important; border-bottom: unset; padding-bottom:unset;">
                        I generatori di prezzi sono matrici grazie alla quale e possibile generare i prezzi intermediari
                        delle offerte
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th></th>
                        <th class="text-center" style="font-size: .9rem;">
                            10k KM / ANNO
                        </th>
                        <th class="text-center" style="font-size: .9rem;">
                            15k KM / ANNO
                        </th>
                        <th class="text-center" style="font-size: .9rem;">
                            20k KM / ANNO
                        </th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td rowspan="2" class="text-center">
                            Anticipo <strong>SI</strong>
                        </td>
                        <td>
                            {!!  Form::text("1&10&24", $pattern['1&10&24'], ['class' => $errors->has('1&10&24') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>
                        <td>
                            {!!  Form::text("1&15&24", $pattern['1&15&24'], ['class' => $errors->has('1&15&24') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>
                        <td>
                            {!!  Form::text("1&20&24", $pattern['1&20&24'], ['class' => $errors->has('1&20&24') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>
                        <td>
                            <strong>24</strong> Mesi
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!!  Form::text("1&10&36", $pattern['1&10&36'], ['class' => $errors->has('1&10&36') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>
                        <td>
                            {!!  Form::text("1&15&36", $pattern['1&15&36'], ['class' => $errors->has('1&15&36') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>
                        <td>
                            {!!  Form::text("1&20&36", $pattern['1&20&36'], ['class' => $errors->has('1&20&36') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>
                        <td>
                            <strong>36</strong> Mesi
                        </td>
                    </tr>
                    <tr>
                        <td rowspan="2" class="text-center">
                            Anticipo <strong>NO</strong>
                        </td>
                        <td>
                            {!!  Form::text("0&10&24", $pattern['0&10&24'], ['class' => $errors->has('0&10&24') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>
                        <td>
                            {!!  Form::text("0&15&24", $pattern['0&15&24'], ['class' => $errors->has('0&15&24') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>

                        <td>
                            {!!  Form::text("0&20&24", $pattern['0&20&24'], ['class' => $errors->has('0&20&24') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>
                        <td>
                            <strong>24</strong> Mesi
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!!  Form::text("0&10&36", $pattern['0&10&36'], ['class' => $errors->has('0&10&36') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>
                        <td>
                            {!!  Form::text("0&15&36", $pattern['0&15&36'], ['class' => $errors->has('0&15&36') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>

                        <td>
                            {!!  Form::text("0&20&36", $pattern['0&20&36'], ['class' => $errors->has('0&20&36') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>
                        <td>
                            <strong>36</strong> Mesi
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="element-wrapper">
    <div class="element-box">
        <div class="element-info">
            <div class="element-info-with-icon">
                <div class="element-info-text">
                    <h5 class="element-inner-header">
                        Generatore Prezzi Web
                    </h5>
                    <div class="form-desc"
                         style="margin-bottom: unset!important; border-bottom: unset; padding-bottom:unset;">
                        I generatori di prezzi sono matrici grazie alla quale e possibile generare i prezzi web delle
                        offerte
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th></th>
                        <th class="text-center" style="font-size: .9rem;">
                            10k KM / ANNO
                        </th>
                        <th class="text-center" style="font-size: .9rem;">
                            15k KM / ANNO
                        </th>
                        <th class="text-center" style="font-size: .9rem;">
                            20k KM / ANNO
                        </th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td rowspan="2" class="text-center">
                            Anticipo <strong>SI</strong>
                        </td>
                        <td>
                            {!!  Form::text("secondary_1&10&24", $secondaryPattern['1&10&24'], ['class' => $errors->has('secondary_1&10&24') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>
                        <td>
                            {!!  Form::text("secondary_1&15&24", $secondaryPattern['1&15&24'], ['class' => $errors->has('secondary_1&15&24') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>
                        <td>
                            {!!  Form::text("secondary_1&20&24", $secondaryPattern['1&20&24'], ['class' => $errors->has('secondary_1&20&24') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>
                        <td>
                            <strong>24</strong> Mesi
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!!  Form::text("secondary_1&10&36", $secondaryPattern['1&10&36'], ['class' => $errors->has('secondary_1&10&36') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>
                        <td>
                            {!!  Form::text("secondary_1&15&36", $secondaryPattern['1&15&36'], ['class' => $errors->has('secondary_1&15&36') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>
                        <td>
                            {!!  Form::text("secondary_1&20&36", $secondaryPattern['1&20&36'], ['class' => $errors->has('secondary_1&20&36') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>
                        <td>
                            <strong>36</strong> Mesi
                        </td>
                    </tr>
                    <tr>
                        <td rowspan="2" class="text-center">
                            Anticipo <strong>NO</strong>
                        </td>
                        <td>
                            {!!  Form::text("secondary_0&10&24", $secondaryPattern['0&10&24'], ['class' => $errors->has('secondary_0&10&24') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>
                        <td>
                            {!!  Form::text("secondary_0&15&24", $secondaryPattern['0&15&24'], ['class' => $errors->has('secondary_0&15&24') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>

                        <td>
                            {!!  Form::text("secondary_0&20&24", $secondaryPattern['0&20&24'], ['class' => $errors->has('secondary_0&20&24') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>
                        <td>
                            <strong>24</strong> Mesi
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!!  Form::text("secondary_0&10&36", $secondaryPattern['0&10&36'], ['class' => $errors->has('secondary_0&10&36') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>
                        <td>
                            {!!  Form::text("secondary_0&15&36", $secondaryPattern['0&15&36'], ['class' => $errors->has('secondary_0&15&36') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>

                        <td>
                            {!!  Form::text("secondary_0&20&36", $secondaryPattern['0&20&36'], ['class' => $errors->has('secondary_0&20&36') ? 'form-control is-invalid' : 'form-control']) !!}
                        </td>
                        <td>
                            <strong>36</strong> Mesi
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
