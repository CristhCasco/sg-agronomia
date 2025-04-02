<div>
    <style></style>


    <div class="row layout-top-spacing">


        <div class="col-sm-12 col-md-8">
            <!--DETALLES -->
            @include('livewire.pos.partials.detail')
            @include('livewire.pos.partials.sales')

        </div>

        <div class="col-sm-12 col-md-4">
            <!--TOTAL -->
            @include('livewire.pos.partials.total')


            <!--PAYMENTS --> 
            @include('livewire.pos.partials.payments')
        
            <!--DENOMINATIONS -->
            @include('livewire.pos.partials.denomination')



        </div>





    </div>
    <!--LLAMA LA VISTA MODAL SEARCH-->
    <livewire:modal-search :componentToEmit="1" />

    <!--LLAMA LA VISTA MODAL CUSTOMER-->
    <livewire:modal-search-customers />

    <script src="{{ asset('js/keypress.js')}}"></script>
    <script src="{{ asset('js/onscan.js') }}"></script>



    <!--SCRIPTS UBICADOS DENTRO DE POS/SCRIPTS -->
    @include('livewire.pos.scripts.shortcuts')
    @include('livewire.pos.scripts.events')
    @include('livewire.pos.scripts.general')
    @include('livewire.pos.scripts.scan')
    @include('livewire.modalimage.modalimage')



    <script>
        document.addEventListener('DOMContentLoaded', function() {
    
            window.livewire.on('global-msg', msg => {
               console.log(msg)
                $('.tblscroll').niceScroll({
                    cursoscolor: "#515365",
                    cursorwidth: "30px",
                    background: "rgba(20,20,20,0.3)",
                    cursorborder: "0px",
                    cursorborderradius: 3
    
                })
            });
            
        })
    
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.addEventListener('print-json-delayed', event => {
                setTimeout(() => {
                    window.dispatchEvent(new CustomEvent('print-json', { detail: event.detail }));
                }, 2000); // Retraso de 2 segundos
            });
        });
    </script>

</div>