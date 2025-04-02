<!-- 
	<script>
		
		try{
		
			onScan.attachTo(document, {
			suffixKeyCodes: [13],
			minLength: 6,
			onScan: function(barcode) {
				console.log(barcode)
				window.livewire.emit('scan-code', barcode)
			},
			onScanError: function(e){
				console.log(e)
			}
		})
		
			console.log('LISTO PARA ESCANEAR')
		
		
		} catch(e){
			console.log('Error de lectura: ', e)
		}
		
		
	</script>
	
	 -->


	 <script>
    try {
        // Configuración del escáner
        onScan.attachTo(document, {
            suffixKeyCodes: [13], // Tecla Enter
            minLength: 6, // Longitud mínima esperada del código de barras
            onScan: function(barcode) {
                // Validar el código de barras antes de emitir el evento
                if (barcode.length >= 6) {
                    console.log(barcode);
                    window.livewire.emit('scan-code', barcode);
                } else {
                    console.log('Código de barras demasiado corto:', barcode);
                }
            },
            onScanError: function(e) {
                console.log(e);
            }
        });
        
        console.log('LISTO PARA ESCANEAR');
    } catch(e) {
        console.log('Error de lectura: ', e);
    }
</script>