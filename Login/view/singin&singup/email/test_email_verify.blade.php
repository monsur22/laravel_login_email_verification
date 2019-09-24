<h3 class="text-center text-success">Email verification Information</h3>

<p> Your Name<b> {{  Session('n')  }}</b> </p>
<p> Your Email "<b> {{  Session('e')  }}</b>" </p>
<p> Your Verify  code<b> {{  Session('code')  }}</b> </p>
<a href="http://localhost:8080/Lara_login/public/emailverify/{{Session('e')}}/{{Session('code')}}">Verify Email</a>