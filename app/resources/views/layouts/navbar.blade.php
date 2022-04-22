<div class="menu-w color-scheme-light color-style-transparent menu-position-side menu-side-left menu-layout-compact sub-menu-style-over sub-menu-color-bright selected-menu-color-light menu-activated-on-hover menu-has-selected-link">

    <div class="menu-actions" style="display: none">
        <div class="messages-notifications os-dropdown-trigger os-dropdown-position-right">
            <i class="os-icon os-icon-mail-14"></i>
            <div class="new-messages-count">+</div>
        </div>
        <div class="messages-notifications os-dropdown-trigger os-dropdown-position-right">
            <i class="os-icon os-icon-mail-14"></i>
            <div class="new-messages-count">+</div>
        </div>
        <div class="messages-notifications os-dropdown-trigger os-dropdown-position-right">
            <i class="os-icon os-icon-mail-14"></i>
            <div class="new-messages-count">+</div>
        </div>
    </div>

    <ul class="main-menu">
        <li class="sub-header">
            <span> {{ __('Offerte') }} </span>
        </li>
        <li class="">
            <a href="{{route('offer.index')}}">
                <div class="icon-w">
                    <div class="os-icon os-icon-cv-2"></div>
                </div>
                <span> {{ __('Noleggio') }} </span></a>
        </li>
        <li class="">
            <a href="{{route('promotion.index')}}">
                <div class="icon-w">
                    <div class="os-icon os-icon-ui-02"></div>
                </div>
                <span> {{ __('Promozioni') }} </span></a>
        </li>
        <li class="">
            <a href="{{route('service.index')}}">
                <div class="icon-w">
                    <div class="os-icon os-icon-tasks-checked"></div>
                </div>
                <span> {{ __('Servizi') }} </span></a>
        </li>

        <li class="sub-header">
            <span> {{ __('Auto') }} </span>
        </li>

        <li class="selected">
            <a href="{{route('car.index')}}">
                <div class="icon-w">
                    <div class="os-icon os-icon-window-content"></div>
                </div>
                <span> {{ __('Allestimenti') }} </span></a>
        </li>

        <li class="selected">
            <a href="{{route('brand.index')}}">
                <div class="icon-w">
                    <div class="os-icon os-icon-agenda-1"></div>
                </div>
                <span> {{ __('Marche') }} </span></a>
        </li>
        <li class="">
            <a href="{{route('image.index')}}">
                <div class="icon-w">
                    <div class="os-icon os-icon-documents-07"></div>
                </div>
                <span> {{ __('Immagini') }} </span></a>
        </li>

        <li class="sub-header">
            <span> {{ __('Utenti') }} </span>
        </li>
        <li class="">
            <a href="{{route('agent.index')}}">
                <div class="icon-w">
                    <div class="os-icon os-icon-users"></div>
                </div>
                <span> {{ __('Account') }} </span>
            </a>
        </li>
        <li class="">
            <a href="{{route('group.index')}}">
                <div class="icon-w">
                    <div class="os-icon os-icon-hierarchy-structure-2"></div>
                </div>
                <span> {{ __('Gruppi') }} </span>
            </a>
        </li>
        <li class="">
            <a href="{{route('commission.index')}}">
                <div class="icon-w">
                    <div class="os-icon os-icon-bar-chart-stats-up"></div>
                </div>
                <span> {{ __('Commissioni') }} </span>
            </a>
        </li>

        <li class="sub-header">
            <span> {{ __('Dati') }} </span>
        </li>
        <li class="">
            <a href="{{route('customer.index')}}">
                <div class="icon-w">
                    <div class="os-icon os-icon-user-male-circle"></div>
                </div>
                <span> {{ __('Clienti') }} </span>
            </a>
        </li>
        <li class="">
            <a href="{{route('quotation.index')}}">
                <div class="icon-w">
                    <div class="os-icon os-icon-phone-21"></div>
                </div>
                <span> {{ __('Preventivi') }} </span>
            </a>
        </li>
        <li class="">
            <a href="{{route('category.index')}}">
                <div class="icon-w">
                    <div class="os-icon os-icon-ui-55"></div>
                </div>
                <span> {{ __('Categorie Contrattuali') }} </span></a>
        </li>

        <li class="sub-header">
            <span> {{ __('Documenti') }} </span>
        </li>
        <li class="">
            <a href="{{route('documents.index')}}">
                <div class="icon-w">
                    <div class="os-icon os-icon-ui-44"></div>
                </div>
                <span> {{ __('Tipi di documenti') }} </span></a>
        </li>
        <li class="">
            <a href="{{route('document-list.index')}}">
                <div class="icon-w">
                    <div class="os-icon os-icon-ui-44"></div>
                </div>
                <span> {{ __('Lista documenti broker') }} </span></a>
        </li>

    </ul>

</div>
