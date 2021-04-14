@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">My Profile</div>

                @if(session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ url('updateUser') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        
                        <div class="form-group{{ $errors->has('default_image') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Defult Image</label>

                            <div class="col-md-6">
                                @if(sizeof($deafultImage)>0)
                                  
                                       <img src="{{url('/')}}/storage/app/public/images/{{$deafultImage->image}}" height="80">

                                        <a href="{{ url('delete-default-image') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('delete-form').submit();">
                                            Delete
                                        </a>

                                  <br>              

                                 <a href="javascript:void(0)" onclick="selectDefaultImage()">Change</a>     
                               @else
                                <a href="javascript:void(0)" onclick="selectDefaultImage()">Upload</a>  
                               @endif 

                                

                                @if ($errors->has('default_image'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('default_image') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">First Name</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control" name="first_name" value="{{ $getUser->first_name }}"  autofocus>

                                @if ($errors->has('first_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                          <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Last Name</label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control" name="last_name" value="{{$getUser->last_name }}"  autofocus>

                                @if ($errors->has('last_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email" value="{{ $getUser->email }}" >

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                    
                         <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Address</label>

                            <div class="col-md-6">
                                <input id="address" type="text" class="form-control" name="address" value="{{ $getUser->address }}" >

                                @if ($errors->has('address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">State</label>

                            <div class="col-md-6">
                                <select id="state_continer" class="form-control" name="state" onchange="getCity()">
                                  @if(sizeof($getState) > 0)
                                        <option value="">Select</option>';
                                         @foreach($getState as $state_list)
                                          <option value="{{$state_list->id}}" @if($state_list->id == $getUser->state) selected="" @endif>{{$state_list->name}}</option>';
                                         @endforeach
                                    @endif
                                </select>


                                @if ($errors->has('state'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                          <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">City</label>

                            <div class="col-md-6">
                                <select id="city_continer" class="form-control" name="city">
                                    <option value="">Select</option>
                                </select>


                                @if ($errors->has('city'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        
                        <div class="form-group{{ $errors->has('subject') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Subject</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control tags" name="subject" value="{{ $getUser->subject }}"/>

                                @if ($errors->has('subject'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('subject') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        
                         <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Image</label>

                            <div class="col-md-6">
                               @if(sizeof($userImages)>0)
                                   @foreach($userImages as $image_list)
                                    <span class="col-md-4" id="image_{{$image_list->id}}">
                                       <img src="{{url('/')}}/storage/app/public/images/{{$image_list->image}}" height="80">
                                       <br>
                                       <a href="javascript:void(0)" onclick="deleteImage('{{$image_list->id}}')">
                                            Delete
                                        </a>
                                    </span>    
                                   @endforeach
                               @endif    

                            </div>
                        </div>

                         <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label"></label>

                            <div class="col-md-6">
                                <input id="image" type="file" class="form-control" name="images[]" value="" multiple="" >

                                @if ($errors->has('image'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="city_id" value="{{ $getUser->city }}">

 @if(sizeof($deafultImage)>0)
<form id="delete-form" action="{{ url('delete-default-image') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
    <input type="hidden" name="id" value="{{$deafultImage->id}}">
</form>
@endif

<form id="default-image-form" method="POST" style="display: none;" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="file" name="image" id="default_image" onchange="uploadDefaultImage()" >
</form>


<script type="text/javascript">

    $('.tags').amsifySuggestags({
        type : 'amsify'
    });

    $( document ).ready(function() {
        getCity();
    });

     function getCity()
    {
        var state_id = $('#state_continer').val();
        var city_id = $('#city_id').val();

         $.ajax({
              url: base_url+"/getCity",
              type: 'POST',
              dataType: 'json',
              data: {id:state_id, city_id:city_id, "_token": "{{ csrf_token() }}"},
              success: function(resp){
                $('#city_continer').html(resp.html);

              }
        });
    }


    function selectDefaultImage()
    {
        $("#default_image").click();
    }

    function uploadDefaultImage()
    {
        
            $.ajax({
              url: base_url+'/upload-default-image',
              data:new FormData($("#default-image-form")[0]),
              dataType:'json',
              async:false,
              type:'post',
              processData: false,
              contentType: false,
              success:function(response){
                if(response.success==true)
                {
                    location.reload();
                }
                else
                {
                    alert(response.msg);
                }

              }
            });
       

    }

    function deleteImage(id)
    {
        if(confirm("Are you sure to delete this image?"))
        {
            $.ajax({
              url: base_url+'/delete-image',
              data:{id:id, '_token':'{{csrf_token()}}'},
              dataType:'json',
              type:'post',
              success:function(response){
                if(response.success==true)
                {
                    $("#image_"+id).hide();
                }
               

              }
            });
        }
         
    }

 </script>
    
@endsection
