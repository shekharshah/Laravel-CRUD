@extends('layouts.app')
@section('content')
<br><a href="{{ route('index') }}" class="btn btn-primary"><i class="glyphicon glyphicon-arrow-left"></i> Back to Main Page</a>
<h1>Laravel Datatables Edit record</h1><hr>
<div class="col-md-4"><br>
  @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif
  {{ Form::model($infodata,['route' => ['update_record',$infodata->id]]) }}
		<div class="form-group">
      <label for="name">Name</label>
      {{ Form::text('name',null,['class'=>'form-control','id'=>'name']) }}
    </div>

    <!--  -->
    
    <?php 

      $cc = ['Audi'=>'Audi','BMW'=>'BMW','Kia'=>'Kia','Honda'=>'Honda','Jaguar'=>'Jaguar','Suzuki'=>'Suzuki'];

      if (!$infodata->car->isEmpty()) {
        $car_select = $infodata->car->pluck('car_name')->toArray();
      }
      else{
        $car_select ="";
      }

    ?>
    <div class="form-group">
      <label for="car_name">Car</label>
      {{ Form::select('car_name[]',$cc,$car_select,['multiple'=>true,'class'=>'form-control','id'=>'car_name']) }}
    </div>

    <!--  -->

    <?php 
      $hobbies = ['Cricket'=>'Cricket','Football'=>'Football','Badminton'=>'Badminton','Volleyball'=>'Volleyball'];

      if (!$infodata->hobby->isEmpty()) {
        $hobby_select = $infodata->hobby->pluck('hobby')->toArray();
      }
      else{
        $hobby_select ="";
      }        
    ?>

    <div class="form-group">
      <label for="hobby">Hobby</label>
        @foreach($hobbies as $key => $value)
          {{ Form::checkbox('hobby[]',$value,$hobby_select,false,['id'=>'hobby']) }} 
          {{ $value }}
        @endforeach
    </div>

    <!--  -->
    <?php
      $languages = ['English'=>'English','Hindi'=>'Hindi','Gujarati'=>'Gujarati'];
    ?>
    <div class="form-group">
      <label for="lang">Languages</label>
      @foreach($languages as $key => $value)
        {{ Form::checkbox('language[]',$value,$language,false,['id'=>'lang']) }}
        {{$value}}
      @endforeach
    </div>
    
    <!--  -->

    <div class="form-group">
    	<input type="submit" value="Update" class="btn btn-primary submit">
  	</div> 
  {{ Form::close() }}
</div>
@endsection
@section('scripts')
<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

</script>
@endsection

