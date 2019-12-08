@extends('layouts.app')
@section('content')
<h1>Laravel Datatables</h1>
	<hr>
	<div class="autohide">
	    @if(session('Success'))
	        <div class="alert alert-success" style="width:50%;overflow: hidden;position: static">
	            {{session('Success')}}
	        </div>
	    @endif
	    
	    @if(session('msg-update'))
	        <div class="alert alert-success" style="width:50%;position: static;overflow: hidden">
	            {{session('msg-update')}}
	        </div>
	    @endif

	    @if(session('msg-delete'))
	        <div class="alert alert-success" style="width:50%;position: static;overflow: hidden">
	            {{session('msg-delete')}}
	        </div>
	    @endif
	</div>
	<!-- delete message -->
	<div class="alert alert-danger delete" style="width:50%;position: static;overflow: hidden;display: none;">
		<strong>Error!</strong> Record Not Deleted...!!
	</div>
	<!-- end -->

	<!-- custom search -->
	<form method="post" id="submitform" role="form">
		<a href="{{route('register')}}" class="btn btn-md btn-primary pull-left"><i class="glyphicon glyphicon-plus"></i> Add Record</a>
		<div class="col-md-4">
			<div class="form-group">
				<input type="text" placeholder="Custom Name Search" name="search" class="form-control">
			</div>
		</div>
		<button type="submit" class="btn btn-success pull-left"><i class="glyphicon glyphicon-search"></i> Search</button>
		<!-- <button type="submit" class="btn btn-danger pull-right form-group delete-all"><i class="glyphicon glyphicon-trash"></i> Delete Selected</button> -->
		
	</form>
	<!-- end -->
	<table class="table table-bordered" id="table">
		<thead>
			<tr>
				<!-- <th><input type="checkbox" name="checkbox[]" class="checked_all"/></th>			 -->
				<th>Id</th>			
				<th>Name</th>			
				<th>Cars</th>		
				<th>Hobbies</th>		
				<th>Languages</th>		
				<th>Email ID</th>			
				<th>Images</th>			
				<th>Actions</th>			
			</tr>
		</thead>
	</table>
@endsection
@section('scripts')
	<script>
	    $.ajaxSetup({
        	headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
	    });
		//custom search submit function
		$('#submitform').on('click','button[type="submit"]',function(){
			table.draw();
			return false;
		});//
		// $(function(){
			var table= $("#table").DataTable({
				processing: true,
				serverSide: true,

				//paging: false, //to hide pagination
				// searching: false, //to hide search bar
				ajax: {
					url: '{!! route('getDataTable') !!}',
					// custom search function
					data: function(d){
						d.name = $('input[name=search]').val();
					}//
				},
				'columnDefs': [ 
					{
						'targets': [0,7], /* column index */
						'orderable': false, /* true or false */
					}
			 	],
				columns:[
					
					// {data: 'check'},
					{data: 'id',name: 'id'},
					{data: 'name',name: 'name'},
					{data: 'cars'},
					{data: 'hobbies',width: '20px'},
					{data: 'language'},
					{data: 'email',name: 'email'},
					{data: 'images',width: '110px'},
					{data: 'actions',name: 'actions'}
				]
			});
		// });
		$(document).on('click','.delete',function(){
	      	var msg=confirm("Are your sure you want to delete");
			if(msg==true){
				var data = $(this).attr("link");
				window.location.href = data;
				// $(".alert-success").show(".alert-success").addClass("Success").fadeOut(4000);
	      	}
	      	else{
				$(".alert-danger").show(".alert-danger").addClass("Danger").fadeOut(4000);
	      	}
		});

        window.setTimeout(function() {
            $(".autohide").fadeTo(5000, 0).slideUp(500, function(){
                $(this).remove();
            });
        }, 500);

        $(document).ready(function(){

	        $('.checked_all').on('change', function() {     
	                $('.checkbox').prop('checked', $(this).prop("checked"));
	        });
	        //deselect "checked all", if one of the listed checkbox product is unchecked amd select "checked all" if all of the listed checkbox product is checked
	        $('.checkbox').change(function(){ //".checkbox" change 
	            if($('.checkbox:checked').length == $('.checkbox').length){
                   	$('.checked_all').prop('checked',true);
	            }
	            else{
            	   	$('.checked_all').prop('checked',false);
	            }
	        });
        });

    </script>
@endsection
