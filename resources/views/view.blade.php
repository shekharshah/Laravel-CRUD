@extends('layouts.app')
@section('content')
<h1>Your Profile</h1><hr>
<div class="col-xs-12 col-sm-8 col-md-8 col-sm-offset-2 col-md-offset-2"><br>
	<a href="{{ route('index') }}" class="btn btn-primary"><i class="glyphicon glyphicon-arrow-left"></i> Back to Main Page</a>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th colspan="2" style="text-align:center;font-size: 25px;"><i class="glyphicon glyphicon-user"></i> Your Details</th>
      </tr>
    </thead>
    <tbody style="text-align:center;font-weight:bold;font-size:120%;">
      
      <tr class="hover">
        <th>Name</th>
        <td>{{ $infodata->name }}</td>
      </tr>

      <tr class="hover">
        <th>Email ID</th>
        <td>{{ $infodata->email }}</td>
      </tr>

      <tr class="hover">
        <th>Cars</th>
        @if(!$infodata->car->isEmpty())
            <?php $car_name = ""; ?>
            @foreach($infodata->car as $key => $value)
                @if($infodata->car->keys()->last() == $key)
                    <?php $app = ""; ?>
                @else
                    <?php $app = ", "; ?>
                @endif
                <?php $car_name .=$value->car_name.''.$app; ?>
            @endforeach
        @else
            <?php $car_name="Null"; ?>
        @endif
        <td>{{ $car_name }}</td>
      </tr>

      <tr class="hover">
        <th>Hobbies</th>
        @if(!$infodata->hobby->isEmpty())
            <?php $hobby_types = ""; ?>
            @foreach($infodata->hobby as $key => $value)
                @if($infodata->hobby->keys()->last() == $key)
                    <?php $app = ""; ?>
                @else
                    <?php $app = ", "; ?>
                @endif
                <?php $hobby_types .=$value->hobby.''.$app; ?>
            @endforeach
        @else
            <?php $hobby_types="Null"; ?>
        @endif
        <td>{{ $hobby_types }}</td>
      </tr>

      <tr class="hover">
        <th>Langauges</th>
        <td>
          @if(!empty($infodata->language))
            {{ $infodata->language }}
          @else
            Null
          @endif
        </td>
      </tr>
      
      <tr class="hover">
        <th>Image</th>
        <td> @if(!$infodata->image->isEmpty())
          <?php $data = ""; ?>
          @foreach($infodata->image as $key => $value)
            <?php 
              $url = url("images/".$value->imagefile);
              // $data .= $url; 
            ?>
            <img id="myImg" src="{{$url}}" height="100px" width=auto />
            <div id="myModal" class="modal">
              <span class="close">&times;</span>
              <img class="modal-content" id="img01">
              <div id="caption"></div>
            </div>
            @endforeach
          @else
            <?php $data="No Image"; ?>
          @endif
          {{ $data }}
        </td>
      </tr>
    </tbody>
  </table>
</div>
@endsection

@section('scripts')
<script>
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  $(document).ready(function(){
    $(".hover").hover(function(){
      $(this).toggleClass("body");
    });
  });
  
  // Get the modal
  var modal = document.getElementById('myModal');

  // Get the image and insert it inside the modal - use its "alt" text as a caption
  var img = document.getElementById('myImg');
  var modalImg = document.getElementById("img01");
  img.onclick = function(){
    modal.style.display = "block";
    modalImg.src = this.src;
  }

  // Get the <span> element that closes the modal
  var span = document.getElementsByClassName("close")[0];

  // When the user clicks on <span> (x), close the modal
  span.onclick = function() { 
    modal.style.display = "none";
  }
</script>
@endsection
