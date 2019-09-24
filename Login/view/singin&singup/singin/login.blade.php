@extends('singin&singup.master_singin&up')
@section('mainContent')

@if(Session::get('login'))


    <script>

      $(document).ready(function(){
     
      $.alert({
                title: 'Message',
                content: 'Account Create Sucessfully',
            });


        });  
    </script>

@endif
@if(Session::get('verify'))


    <script>

      $(document).ready(function(){
     
      $.alert({
                title: 'Message',
                content: 'Verify Your Email',
            });


        });  
    </script>

@endif
@if(Session::get('update'))


    <script>

      $(document).ready(function(){
     
      $.alert({
                title: 'Message',
                content: 'Password Update Successfully',
            });


        });  
    </script>

@endif
    <form class="form-login" method="POST" action="{{url('/login/action')}}">
      {{ csrf_field() }}
        <h2 class="form-login-heading">sign in now</h2>
        <div class="login-wrap">
          <input type="email" class="form-control" placeholder="Email" name="email" autofocus>
        
          <br>
          
               <input type="password" class="form-control" placeholder="Password" name="password" id="showPass" >
               <span toggle="#password-field" class="fa fa-lg fa-eye field-icon toggle-password"  onclick="myFunction()"></span>
            
          <label class="checkbox">
            
            <span class="pull-right">
            <a  href="{{url('/For-Email')}}"> Forgot Password?</a>
            </span>
            </label>
          <button class="btn btn-theme btn-block" name="btn" type="submit"><i class="fa fa-lock"></i> SIGN IN</button>
          <hr>
          <div class="login-social-link centered">
            <p>or you can sign in via your social network</p>
          <a class="btn btn-facebook" href="{{url('login/facebook')}}" type="submit"><i class="fa fa-facebook"></i> Facebook</a>
            <button class="btn btn-twitter" type="submit"><i class="fa fa-twitter"></i> Twitter</button>
          </div>
          <div class="registration">
            Don't have an account yet?<br/>
            <a href="{{url('/singup')}}">
              Create an account
              </a>
          </div>
        </div>
      
     
      </form>


    
@endsection