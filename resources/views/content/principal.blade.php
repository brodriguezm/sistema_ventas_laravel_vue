
@extends('layout')
@section('content')
<example-component v-if="IdMenu==0"></example-component>
<h1 v-if="IdMenu==1"> Saludos desde menu 1</h1>
<h1 v-if="IdMenu==2"> Saludos desde menu 2</h1>
<h1 v-if="IdMenu==12"> Saludos desde menu 12</h1>
@endsection