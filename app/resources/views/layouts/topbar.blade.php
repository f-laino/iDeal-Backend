<div class="top-bar color-scheme-light">
    <div class="logo-w menu-size">
        <a class="logo" href="{{route('home')}}">
                <img src="{{ URL::to('https://cdn1.carplanner.com/ideal/logo.png') }}" style="height: 30px;">
        </a>
    </div>
    <div class="top-menu-controls">
        @isset($searchRoute)
        <div class="element-search autosuggest-search-activator">
            <input placeholder="Digita per cercare..." type="text">
        </div>
        @endisset

        <div class="top-icon top-settings os-dropdown-trigger os-dropdown-position-left">
            <i class="os-icon os-icon-ui-46"></i>
            <div class="os-dropdown">
                <div class="icon-w">
                    <i class="os-icon os-icon-ui-46"></i>
                </div>
                <ul>
                    <li>
                        <a  href="{{route('home', [ "id" => (int) Auth::id()])}}">
                            <i class="os-icon os-icon-user-male-circle2"></i>
                            <span>{{ __('Impostazioni') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('logout')}}">
                            <i class="os-icon os-icon-signs-11"></i>
                            <span>{{ __('Log Out') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@isset($searchRoute)
<div class="search-with-suggestions-w">
    <div class="search-with-suggestions-modal">
        {!!  Form::open(['route' => $searchRoute,  "id" => "search-form", 'method' => 'get'])  !!}
        <div class="element-search">
            {{ Form::text("q", null, ['class' => 'search-suggest-input', 'id' => 'q', "placeholder"=> 'Digita per cercare...']) }}

            <div class="close-search-suggestions">
                <i class="os-icon os-icon-x"></i>
            </div>
            </input>
        </div>
        <div class="search-suggestions-group">
            @yield('searchExtraFields')
        </div>

        {!! Form::submit('Cerca', ['class' => 'float-right btn btn-primary btn-rounded', "style"=>"margin-top:-10px;width:100%"]) !!}
        {!! Form::close()!!}
</div>
</div>
@endisset
