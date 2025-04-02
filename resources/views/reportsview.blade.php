@extends('layouts.theme.app')

@section('content')
<div>
    <div class="row sales layout-top-spacing">
        <div class="col-sm-12">
            <div class="widget">
                <div class="widget-heading">
                    <h4 class="card-title text-center"><b>REPORTES DE SISTEMA</b></h4>
                </div>
                <div class="widget-content">
                    <div class="row">
                        <div class="col-sm-12 col-md-3">
                            <div class="card">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-3">Ventas e Ingresos</h5>
                                    <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('sales.reports') }}" class="btn btn-primary btn-block">Ver Reporte</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <div class="card">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-3">Compras a Proveedor</h5>
                                    <i class="fas fa-truck fa-2x mb-2"></i>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('purchases.reports') }}" class="btn btn-primary btn-block">Ver Reporte</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <div class="card">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-3">Inventario de Productos</h5>
                                    <i class="fas fa-boxes fa-2x mb-2"></i>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('inventory.reports') }}" class="btn btn-primary btn-block">Ver Reporte</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection