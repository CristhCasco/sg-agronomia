<script src="{{ asset('assets/js/loader.js') }}"></script>
<link href="{{ asset('assets/css/loader.css') }}" rel="stylesheet" type="text/css" />

<link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">

<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/plugins.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/structure.css') }}" rel="stylesheet" type="text/css" class="structure" />

<link href="{{ asset('plugins/font-icons/fontawesome/css/fontawesome.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/fontawesome.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ asset('assets/css/elements/avatar.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ asset('plugins/sweetalerts/sweetalert.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('plugins/notification/snackbar/snackbar.min.css') }}" rel="stylesheet" type="text/css" />


<link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/widgets/modules-widgets.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/forms/theme-checkbox-radio.css') }}">

<link href="{{ asset('assets/css/apps/scrumboard.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/apps/notes.css') }}" rel="stylesheet" type="text/css" />

<!--  BEGIN CUSTOM STYLE FILE  -->
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/flatpickr/flatpickr.css')}}">

<!--STYLE FOR DASHBOARD-->
<link rel="stylesheet" type="text/css" href="{{ asset('css/apexcharts.css')}}">

<style>

    aside{
        display: none;
    }

    .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: #620408;
        border-color: #620408;
    }

    @media (max-width: 480px) {
        .mtmobile{
            margin-bottom: 20px!important;
        }
        .mbmobile{
            margin-bottom: 10px!important;
        }
        .hideonsm{
            display: none!important;
        }
        .inblock{
            display:block;
        }
    }

    .bg-dark {
    background-color: #620408!important;
    }

    .input-gp {
    background: #620408!important;
    color: white!important;
    }

    h1, h2, h3, h4, h5, h6 {
    color: #620408;
    }

    #colorPallet .pallet-icon {
    display: inline-block;
    align-self: center;
    padding: 8px;
    /* background: #d3d3d3; */
    /* color: #191e3a; */
    background: #1b2e4b;
    color: #fff;
    cursor: pointer;
    border-top-left-radius: 6px;
    border-bottom-left-radius: 6px;
    border-right: 1px solid rgba(92, 26, 195, 0.14);
}



</style>

@livewireStyles

