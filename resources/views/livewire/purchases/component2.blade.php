<div>
    <style></style>


    <div class="row layout-top-spacing">


        <div class="col-sm-12 col-md-8">
            <!--DETALLES -->
            @include('livewire.purchases.partials.detail')

        </div>

        <div class="col-sm-12 col-md-4">
            <!--TOTAL -->
            @include('livewire.purchases.partials.total')


            <!--PAYMENTS -->
            @include('livewire.purchases.partials.payments')


            <!--DENOMINATIONS -->
            @include('livewire.purchases.partials.denomination')




        </div>





    </div>
    <!--LLAMA LA VISTA MODAL SEARCH-->
    <livewire:modal-search :componentToEmit="2" />

    <!--LLAMA LA VISTA MODAL CUSTOMER-->
    <livewire:modal-search-suppliers />

    <script src="{{ asset('js/keypress.js')}}"></script>
    <script src="{{ asset('js/onscan.js') }}"></script>



    <!--SCRIPTS UBICADOS DENTRO DE purchases/SCRIPTS -->
    @include('livewire.purchases.scripts.shortcuts')
    @include('livewire.purchases.scripts.events')
    @include('livewire.purchases.scripts.general')
    @include('livewire.purchases.scripts.scan')

</div>