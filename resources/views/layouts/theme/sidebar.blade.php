
<div class="sidebar-wrapper sidebar-theme ">

    <nav id="compactSidebar">
        
        <ul class="menu-categories">
            
            <li class="active">
                <a href="{{url('categories')}}" class="menu-toggle" data-active="true" >
                    <div class="base-menu">
                        <div class="base-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                        </div>
                        <span>CATEGORIAS</span>
                    </div>
                </a>
            </li>
            
          

            <li class="">
                <a href="{{url('products')}}" class="menu-toggle" data-active="false" >
                    <div class="base-menu">
                        <div class="base-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                        </div>
                        <span>PRODUCTOS</span>
                    </div>
                </a>
            </li>

            <li class="">
                <a href="{{ url('purchases') }}" class="menu-toggle" data-active="false" >
                    <div class="base-menu">
                        <div class="base-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                        </div>
                        <span>COMPRAS</span>
                    </div>
                </a>
            </li>

            <li class="">
                <a href="{{ url('pos') }}" class="menu-toggle" data-active="false" >
                    <div class="base-menu">
                        <div class="base-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                        </div>
                        <span>VENTAS</span>
                    </div>
                </a>
            </li>

                <!-- NUEVO MENÚ DE CRÉDITOS -->
                <li class="">
                    <a href="javascript:void(0);" class="menu-toggle">
                        <div class="base-menu">
                            <div class="base-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                            </div>
                            <span>CRÉDITOS</span>
                        </div>
                    </a>
                    <ul class="submenu">
                        <li>
                            <a href="{{ url('credits/sales') }}">
                                <span>VENTAS</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('credits/purchases') }}">
                                <span>COMPRAS</span>
                            </a>
                        </li>
                    </ul>
                </li>

            <li class="">
                <a href="{{ url('customers') }}" class="menu-toggle" data-active="false" >
                    <div class="base-menu">
                        <div class="base-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        </div>
                        <span>CLIENTES</span>
                    </div>
                </a>
            </li>

            <li class="">
                <a href=" {{ url('suppliers') }}" class="menu-toggle" data-active="false" >
                    <div class="base-menu">
                        <div class="base-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                        </div>
                        <span>PROVEEDORES</span>
                    </div>
                </a>
            </li>
            @role('admin')
            <li class="">
                <a href="{{url('users')}}" class="menu-toggle" data-active="false" >
                    <div class="base-menu">
                        <div class="base-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                        <span>USUARIOS</span>
                    </div>
                </a>
            </li>
            @endcan
            
            @role('admin')
            <li class="">
                <a href="{{ url('roles')}}" class="menu-toggle" data-active="false" >
                    <div class="base-menu">
                        <div class="base-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </div>
                        <span>ROLES</span>
                    </div>
                </a>
            </li>
            @endcan

            @role('admin')
            <li class="">
                <a href="{{ URL('permissions') }}" class="menu-toggle" data-active="false" >
                    <div class="base-menu">
                        <div class="base-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                        </div>
                        <span>PERMISOS</span>
                    </div>
                </a>
            </li>
            @endcan

            
            <li class="">
                <a href="{{ url('assign') }}" class="menu-toggle" data-active="false" >
                    <div class="base-menu">
                        <div class="base-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-toggle-right"><rect x="1" y="5" width="22" height="14" rx="7" ry="7"></rect><circle cx="16" cy="12" r="3"></circle></svg>
                        </div>
                        <span>ASIGNAR</span>
                    </div>
                </a>
            </li>
           

            @role('admin')
            <li class="">
                <a href="{{url('denominations')}}" class="menu-toggle" data-active="false" >
                    <div class="base-menu">
                        <div class="base-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-stop-circle"><circle cx="12" cy="12" r="10"></circle><rect x="9" y="9" width="6" height="6"></rect></svg>
                        </div>
                        <span>MONEDAS</span>
                    </div>
                </a>
            </li>
            @endcan

            <li class="">
                <a href="{{ url('cashout') }}" class="menu-toggle" data-active="false" >
                    <div class="base-menu">
                        <div class="base-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        </div>
                        <span>ARQUEOS</span>
                    </div>
                </a>
            </li>

            <li class="">
                <a href="{{ url ('reports') }}" class="menu-toggle" data-active="false" >
                    <div class="base-menu">
                        <div class="base-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
                        </div>
                        <span>REPORTES</span>
                    </div>
                </a>
            </li>

            <li class="">
                <a href="{{ url('inventory') }}" class="menu-toggle" data-active="false" >
                    <div class="base-menu">
                        <div class="base-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M20 6.82V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v.82"></path><path d="M3 10.82V10a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v.82"></path><path d="M12 18l-2 2-2-2"></path></svg>
                        </div>
                        <span>INVENTARIO</span>
                    </div>
                </a>



            

        </ul>

    </nav>

</div>

<div id="compact_submenuSidebar" class="submenu-sidebar" style="display: none!important"> </div>


<style>
    
    .menu-categories > li > a {
    color: #fff;
    display: block;
    padding: 10px 15px;
    font-size: 15px;
    transition: background 0.3s ease;
    }

    /* Hover del menú principal */
    .menu-categories > li > a:hover {
        background-color: #2a2a2a;
        border-radius: 5px;
    }

    /* Ocultar el submenu por defecto */
    ul.submenu {
        display: none;
        padding-left: 20px;
    }

    /* Mostrar submenu cuando el li tiene clase "open" */
    li.open > ul.submenu {
        display: block;
    }

    ul.submenu li a {
    color: #fff;
    padding: 8px 15px;
    display: block;
    font-size: 14px;
    transition: background 0.3s ease;
    }

    ul.submenu li a:hover {
        background-color: #2a2a2a; /* Cambia este color si querés otro fondo */
        border-radius: 5px;
    }

    /* ul.submenu li a:hover span {
    color: #ffd700; /* Ejemplo: dorado al pasar el mouse */
    } */



</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Buscar todos los toggles del menú
        document.querySelectorAll('.menu-toggle').forEach(toggle => {
            toggle.addEventListener('click', function (e) {
                const parentLi = this.closest('li');
                parentLi.classList.toggle('open');
            });
        });
    });
</script>
