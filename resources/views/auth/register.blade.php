@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('processRegistration') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">First Name</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}"  autofocus>

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
                                <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}"  autofocus>

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
                                <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" >

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
                                <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}" >

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
                                <input type="text" class="form-control tags" name="subject" value="{{ old('subject') }}"/>

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
                                <input id="image" type="file" class="form-control" name="images[]" value="" multiple="" >

                                @if ($errors->has('image'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" >

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" >
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="old_state" name="state" value="{{ old('state') }}"/>
<input type="hidden" id="old_city" name="city" value="{{ old('city') }}"/>


<script type="text/javascript">

    $('.tags').amsifySuggestags({
        type : 'amsify'
    });

    $( document ).ready(function() {
        getState();
    });

    function getState()
    {
        $.ajax({
              url: base_url+"/getState",
              type: 'POST',
              dataType: 'json',
              data: {"_token": "{{ csrf_token() }}"},
              success: function(resp){
                $('#state_continer').html(resp.html);

                var old_state = $("#old_state").val();
                $('#state_continer').find("option[value='"+old_state+"']").attr("selected","selected");

                getCity();
              }
        });
        
    }

    function getCity()
    {
        var state_id = $('#state_continer').val();

         $.ajax({
              url: base_url+"/getCity",
              type: 'POST',
              dataType: 'json',
              data: {id:state_id, "_token": "{{ csrf_token() }}"},
              success: function(resp){
                $('#city_continer').html(resp.html);

                 var old_city = $("#old_city").val();
                $('#city_continer').find("option[value='"+old_city+"']").attr("selected","selected");
              }
        });
    }
    
</script>

@endsection
