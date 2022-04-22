<p>
    In questa sezione è possibile selezionare i servizi aggiuntivi (a pagamento) che appariranno nelle offerte di questo gruppo.
</p>

<div style="display: flex;flex-flow: column wrap;height:130px;">
    @foreach($additionalServices as $additionalService)
    <label for="service-{{$additionalService->id}}" style="height: 30px;">
    {!!
        Form::checkbox('services[]', $additionalService->id, $selectedServices->contains($additionalService->id), [
            'class' => $errors->has('services') ? 'form-control is-invalid' : 'form-control',
            'id' => 'service-' . $additionalService->id
        ])
    !!}
    {{ $additionalService->name }} {{ $additionalService->price }}€
    </label>
    @endforeach
</div>
