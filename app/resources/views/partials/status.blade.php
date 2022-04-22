@if (Session::has('success'))
    <div class="alert alert-success" role="alert">
        <strong>Ben fatto! </strong>{{ Session::get('success') }}
    </div>
@elseif(Session::has('error'))
    <div class="alert alert-danger" role="alert">
        <strong>Caspita! </strong>{{ Session::get('error') }}
    </div>
@endif
