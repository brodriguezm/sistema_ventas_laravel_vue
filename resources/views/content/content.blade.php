
@extends('layout')
@section('content')
    <div v-if="IdMenu==0" style="text-align: center;padding: 20em 0;width: 100%;">
        <p style="font-size: 8em;color:#00000024;">Bienvenido</p>
    </div>

    <categoria v-if="IdMenu==1"></categoria>

    <articulo v-if="IdMenu==2"></articulo>

    <template v-if="IdMenu==3">
        <h1>Ingresos</h1>
    </template>

    <proveedor v-if="IdMenu==4"></proveedor>

    <template v-if="IdMenu==5">
        <h1>Ventas</h1>
    </template>

    <cliente v-if="IdMenu==6"></cliente>

    <user v-if="IdMenu==7"></user>

    <rol v-if="IdMenu==8"></rol>

    <template v-if="IdMenu==9">
        <h1>Reporte de Ingresos</h1>
    </template>

    <template v-if="IdMenu==10">
        <h1>Reporte de Ingresos</h1>
    </template>

    <template v-if="IdMenu==11">
        <h1>Ayuda</h1>
    </template>

    <template v-if="IdMenu==12">
        <h1>Acerca de </h1>
    </template>

@endsection