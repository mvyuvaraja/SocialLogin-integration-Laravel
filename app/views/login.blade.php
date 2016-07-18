<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="itsyourskills">
        <meta name="_token" content="{{ csrf_token() }}" />
        <title>Login</title>
        <link href="{{asset('/css/font-awesome.min.css') }}" rel="stylesheet" />
        <link href="{{asset('/css/bootstrap.min.css') }}" rel="stylesheet" />
        <link href="{{asset('/css/bootstrapValidator.min.css') }}" rel="stylesheet" />
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-lg-5 col-sm-12 col-md-offset-3">
                    <div class="login-grid">
                        <div class="page-header"><strong>Login</strong></div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div id="ajax-message"></div>
                            {{ Form::open(array('url' => '/login',  'id' => 'loginform', 'method'=>'POST')) }}
                            <div class="form-group">
                                <label>Email <span class="text-danger">*</span></label>
                                {{ Form::text('email', null, array('class' => 'form-control', 'id'=>'email','placeholder' => 'Email')) }}
                                <span class="help-block"></span>
                            </div>
                            <div class="form-group">
                                <label>Password <span class="text-danger">*</span></label>
                                {{ Form::password('password', array('class' => 'form-control', 'id' => 'password','placeholder' => 'Password')) }}
                                <span class="help-block"></span>
                            </div>
                            <a href="/register">Create an account</a>
                            {{ Form::button('Sign in', ['class' => 'btn btn-info pull-right', 'id' => 'loginBtn'])}}
                            {{ Form::close() }}
                        </div>
                        <hr>
                        <div class="col-lg-12 col-md-12 col-sm-12 bdr-right text-center">
                            <h5>Login with</h5>
                            <a class="btn btn-primary" href="{{URL::to('/social-login/facebook')}}"><i class="fa fa-facebook"></i> | Facebook</a>
                            <a class="btn btn-danger" href="{{URL::to('/social-login/google')}}"><i class="fa fa-google"></i> | Google</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="{{asset('js/jquery.min.js')}}"></script>
        <script src="{{asset('js/bootstrap.min.js')}}"></script>
        <script src="{{asset('js/bootstrapValidator.min.js')}}"></script>
        <script>
            $(function () {
                $('#loginBtn').off('click');
                $('#loginBtn').on('click', function(){
                    $('#loginform').submit();
                });
                
                //Login Form validation
                $('#loginform').bootstrapValidator({
                    fields: {
                        email: {
                            validators: {
                                notEmpty: {
                                    message: ' '
                                },
                                emailAddress: {
                                    message: 'Please enter valid email address'
                                }
                            }
                        },
                        password: {
                            validators: {
                                notEmpty: {
                                    message: ' '
                                }

                            }
                        }

                    }
                }).on('success.form.bv', function (e) {
                    e.preventDefault();
                    $('#loginBtn').html('<i class="fa fa-refresh fa-spin"></i>  Authenticating');
                    var form = $(e.target);
                    var url = form.attr("action");
                    var type = form.attr("method");
                    var data = form.serialize();
                    $.ajax({
                        url: url,
                        dataType: 'json',
                        type: type,
                        data: data,
                        success: function (res) {
                            if(res.valid == 1){
                                window.location.href = '/dashboard';
                            } else {
                                $('#loginBtn').html('Sign in');
                                $('#ajax-message').html('<div class="alert alert-danger alert-dismissable" ><p>' + res.message + '</p></div>');
                            }
                        }
                    });
                });
            });
        </script>
    </body>
</html>
