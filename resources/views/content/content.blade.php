
@extends('layout')
@section('content')
    <div v-if="IdMenu==0" style="text-align: center;padding: 20em 0;width: 100%;">
        <p style="font-size: 8em;color:#00000024;">Bienvenido</p>
    </div>

    <categoria v-if="IdMenu==1"></categoria>

    <articulo v-if="IdMenu==2"></articulo>

    <proveedor v-if="IdMenu==4"></proveedor>

    <cliente v-if="IdMenu==6"></cliente>

    <rol v-if="IdMenu==8"></rol>

    <h1 v-if="IdMenu==12"> Saludos desde menu 12</h1>
@endsection