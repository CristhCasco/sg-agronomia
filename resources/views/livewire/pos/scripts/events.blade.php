
<script>
	document.addEventListener('DOMContentLoaded', function() {
		
		window.livewire.on('scan-ok', Msg => {			
			noty(Msg)
		})

		window.livewire.on('scan-notfound', Msg => {			
			noty(Msg, 2)
			doAction()
		})

		window.livewire.on('no-stock', Msg => {			
			noty(Msg, 2)
		})

		window.livewire.on('sale-ok', Msg => {	
			console.log('sale-ok')	
		//@this.printTicket(Msg)		
		noty(Msg)			
		})

		window.livewire.on('sale-error', Msg => {			
			noty(Msg, 2)
		})


		

		// METODO PROBADO
		//   window.livewire.on('print-json', b64 => {
		//   console.log('Print Data: ', b64);
		//   window.open("print://" + b64, '_self').close();
		//   })


		  //OTRO METODO
		  window.addEventListener('print-json', event => {   
        	console.log(event.detail.data);
        	silentMode(event.detail.data)
    	})

		let iframe = null
		function silentMode(sale){
			
			if(!iframe) {
			iframe = document.createElement('iframe')


				// atributos
				iframe.style.width = '0px'
				iframe.style.height = '0px'
				iframe.style.border = '0'
				document.body.appendChild(iframe)

			}
			iframe.src ="print://" + sale

		}



		 window.livewire.on('print-ticket', saleId => {	
		
		 if(getBrowser() !='edge'){
		 	window.open("print://" + saleId,  '_self').close()
		 } else {
		 	 window.open("print://" + saleId)
		 	//obj.close()
		 }

		})
		window.livewire.on('print-last-id', saleId => {					
			window.open("print://" + saleId,  '_self')
			//window.open("print://" + saleId,  '_self').close()//en chrome cierra la ventana
		})
		

	})
</script>

<script>
//console.log(window.navigator.userAgent.toLowerCase().indexOf("edge"))
function getBrowser(agent) {
	var agent = window.navigator.userAgent.toLowerCase()
	switch (true) {
		case agent.indexOf("edge") > -1: return "edge";
		case agent.indexOf("edg") > -1: return "chromium based edge (dev or canary)";
		case agent.indexOf("opr") > -1 && !!window.opr: return "opera";
		case agent.indexOf("chrome") > -1 && !!window.chrome: return "chrome";
		case agent.indexOf("trident") > -1: return "ie";
		case agent.indexOf("firefox") > -1: return "firefox";
		case agent.indexOf("safari") > -1: return "safari";
		default: return "other";
	}
}
  // console.log(getBrowser())
</script>
