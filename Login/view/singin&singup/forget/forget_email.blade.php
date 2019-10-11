<h3 class="text-center text-success">Password verification Information</h3>


<p> Your Email "<b> {{  Session('e')  }}</b>" </p>
<p> Your Reset Password  code<b> {{  Session('code')  }}</b> </p>
<a href="http://localhost:8080/Lara_login/Forget-vefify-email/{{Session('e')}}/{{Session('code')}}">Update Password</a>
