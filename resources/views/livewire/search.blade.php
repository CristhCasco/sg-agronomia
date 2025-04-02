<div>
    <ul class="navbar-item flex-row search-ul">
        <li class="nav-item align-self-center search-animated">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search toggle-search text-white">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <form class="form-inline search-full" role="search">
                <div class="search-bar">
                    <input id="code" type="text"
                    wire:keydown.enter.prevent="$emit('scan-code', $('#code').val())"
                    class="form-control search-form-control ml-lg-auto" placeholder="Buscar...">
                </div>
            </form>
        </li>
    </ul>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            livewire.on('scan-code', action => {
                $('#code').val('')
            });

            const scanInput = document.getElementById('code');

            scanInput.addEventListener('focus', function() {
                onScan.detachFrom(document);
            });

            scanInput.addEventListener('blur', function() {
                onScan.attachTo(document, {
                    suffixKeyCodes: [13],
                    minLength: 6,
                    onScan: function(barcode) {
                        console.log(barcode);
                        window.livewire.emit('scan-code', barcode);
                    },
                    onScanError: function(e) {
                        console.log(e);
                    }
                });
            });
        });
    </script>
</div>