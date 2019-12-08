@extends('layouts.app')
@section('content')
<br><a href="{{ route('index') }}" class="btn btn-primary"><i class="glyphicon glyphicon-arrow-left"></i> Back to Main Page</a>
<h1>Laravel Datatables Add record</h1><hr>
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
  {!! Form::open(['url'=>route('store'),'id'=>'registerform','enctype' => 'multipart/form-data']) !!}


  		<div class="form-group">
  			<label for="name">Name</label><label id="required">*</label>
          {{ Form::text('name',null,['class'=>'form-control','id'=>'name','placeholder'=>'Enter your name']) }}
  		</div>

  		<div class="form-group">
  	  		<label for="email">EmailID</label><label id="required">*</label>
          {{ Form::email('email',null,['class'=>'form-control','id'=>'email','placeholder'=>'Enter your Email Address']) }}
  		</div>

  		<div class="form-group">
  	  		<label for="password">Password</label><label id="required">*</label>
          {{ Form::password('password',['class'=>'form-control','id'=>'password','placeholder'=>'Enter your Password']) }}
  		</div>
      <?php 
        $cars = ['Audi'=>'Audi','BMW'=>'BMW','Kia'=>'Kia','Honda'=>'Honda','Jaguar'=>'Jaguar','Suzuki'=>'Suzuki'];
      ?>
      <div class="form-group">
          <label for="car_name">Car</label>
          {{ Form::select('car_name[]',$cars,null,['multiple'=>true,'class'=>'form-control','id'=>'car_name']) }}
          <b>You selected: </b><span id="droptext"></span>
      </div>

      <?php
        $hobbies = ['Cricket'=>'Cricket','Football'=>'Football','Badminton'=>'Badminton','Volleyball'=>'Volleyball'];
      ?>
      <div class="form-group">
          <label for="hobby">Hobby</label>
            @foreach($hobbies as $key => $value)
              {{ Form::checkbox('hobby[]',$value,false,['class'=>'hobby']) }} 
              {{ $value }}
            @endforeach
          <p id="message"></p>
      </div>

      <?php 
        $languages = ['English'=>'English','Hindi'=>'Hindi','Gujarati'=>'Gujarati'];
      ?>
      <div class="form-group">
          <label for="lang">Languages</label>
          @foreach($languages as $key => $value)
            {{ Form::checkbox('language[]',$value,false) }}
            {{$value}}
          @endforeach
      </div>

      <div class="form-group">
        <label for="fileupload">Upload Image</label><label id="required">*</label>
        {{ Form::file('image[]',['multiple'=>true,'class'=>'form-control','id'=>'files','onchange'=>'previewFile()']) }}
        <!-- <img src="" title="" height="100px" width="160px" style="display: none;"> -->
        <div id="selectedFiles"></div>
      </div>

      <!-- <div class="form-group">
        <label for="fileupload">Status</label>
        {{ Form::number('status',null,['class'=>'form-control','min'=>'0','max'=>'1']) }}
      </div> -->

      <!-- <div class="form-group">
          <label for="animals">Animals</label>
          {{ Form::select('animals',[
              'Cats' => ['leopard' => 'Leopard'],
              'Dogs' => ['spaniel' => 'Spaniel','huskey'=>'Huskey'],
            ],null,['class'=>'form-control','id'=>'car'])
          }}
      </div>

      <div class="form-group">
          <label for="gender">Gender</label>
          {{ Form::radio('gender','male',true,['id'=>'gender']) }} Male
          {{ Form::radio('gender','female',false,['id'=>'gender']) }} Female
      </div>

      <div class="form-group">
          <label for="age">Age</labageel>
          {{ Form::selectRange('age',10,20,null,['class'=>'form-control','id'=>'age']) }}
      </label>
      
      <div class="form-group">
          <label for="dob">Date Of Birth</label>
          {{ Form::date('dob',null,['class'=>'form-control','id'=>'dob']) }}
      </div>
      
      <div class="form-group">
          <label for="status">Status</label>
          {{ Form::number('status',null,['max'=>1,'min'=>0,'class'=>'form-control']) }}
      </div> -->
      
      <div class="form-group">
      	<input type="submit" value="Submit" class="btn btn-primary submit">
    	</div> 

  {!! Form::close() !!}
</div>
@endsection
@section('scripts')
<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
  //code for single selected image preview
  // function previewFile() {
  //   var preview = document.querySelector('img');
  //   var file = document.querySelector('input[type=file]').files[0];
  //   var reader = new FileReader();
  //   reader.addEventListener("load", function() {
  //     console.log('before preview');
  //     preview.src = reader.result;
  //     console.log('after preview');
  //   }, false);

  //   if (file) {
  //     console.log('inside if');
  //     reader.readAsDataURL(file);
  //     $("img").show();
  //   } else {
  //     console.log('inside else');
  //   }
  // }
  
  // code for multiple selected image preview in form
  var div = "";
  document.addEventListener("DOMContentLoaded", init, false);
  function init() {
    document.querySelector('#files').addEventListener('change', handleFileSelect, false);
    div = document.querySelector("#selectedFiles");
  }
    
  function handleFileSelect(e) {
    if(!e.target.files || !window.FileReader) return;
    div.innerHTML = "";
    
    var files = e.target.files;
    var filesArr = Array.prototype.slice.call(files);
    filesArr.forEach(function(f,i) {
      var f = files[i];
      if(!f.type.match("image.*")) {
        return;
      }

      var reader = new FileReader();
      reader.onload = function (e) {
        var picFile = e.target.result;
        var html = f.name + "<img class='thumbnail' src='" + picFile + "' alt='"+f.name+"' height='100px' width='auto'>";
        div.innerHTML += html;       
      }
      reader.readAsDataURL(f); 
    });
  }
  //

  $(document).ready(function(){

    //car selected
    $(document).on("change","#car_name",function(){
      var multiselect=[];
      $('select#car_name option:selected').each(function(i,v){
        multiselect.push($(this).val());
      }); 
      $("#droptext").html(multiselect.filter(Boolean));
      $("#droptext").html(multiselect.join(", "));
    });

    $(".hobby").on("change",function(){
      var checkbox=[];
      $('.hobby:checked').each(function(i,v){
        checkbox.push($(this).val());
      }); 
      $("#message").html(checkbox.filter(Boolean));
      $("#message").html(checkbox.join(", "));
    });

    var name=$('#name').val();
 
    $("#registerform").validate({
      rules:{
        name:{
          required:true,
          remote:{
            type: 'POST',
            url: "{{route('checkNameExists')}}",
            data:{
              _token: '{{csrf_token()}}',name:function(){
                return $('#name').val();
              }
            }
          }
        },
        email:{
          required: true
        },
        password:{
          required: true,
          minlength : 6
        },
        image:{
          required: true
        },
      },
      messages:{
        name:{
          required:"Name is required",
          remote:'Name already exists',
        },
        email:{
          required:"Email is required",
        },
        password:{
          required:"Password is required",
          minlength:"Password cannot be less than 6 characters",
        },
        image:{
          required:"Please upload an image",
        }
      },
      submitHandler: function(form) {
        $(".submit").attr("disabled", true);
        form.submit();
      }
    });
  
  });
</script>
@endsection

