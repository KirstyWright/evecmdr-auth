@extends('layouts.app')

@section('content')
<div style="position:absolute;top:0px;left:0px;width:100%;height:100%;background:url('/images/bg.jpg') center center;">
</div>
<div class="container" style="color:#FFF;">
   <div class="row text-center " style="padding-top:20%;">
      <div class="col-md-12">
      </div>
   </div>
   <div class="row">
      <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
         <div class="panel-body" style="background-color:rgba(0,0,0,0.2)">
			<a href="/auth"><img class="center-block" src="/images/sso.png" /></a>
		  </div>
      </div>
   </div>
</div>
@endsection
