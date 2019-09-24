<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\verify_token;
use App\Singup;
use Mail;
use Hash;
use Validator;
use Redirect;
use Session;
use DB;
use Socialite;


class singinupController extends Controller
{
    

        public function singuppageView(){
            return view('singin&singup.singup.singup');
                    
            }
    public function singuppage(Request $request){
        
        $this->Validate($request, [
            'email' => 'required',      
            'password' => 'required',      
                 
       ]);

       
    
        if($checkemail=Singup::where('email',$request->email)->first()){
            return redirect('/singemail')->with('taken',' Email  taken ');
            //return "email_taken";
                
    
    
            }
            else{
              if( $id=verify_token::where('email',$request->email)->first()){
               $id->delete();
              }
                
           $token= new verify_token(); 
    
            $random=rand();
            $token->token = $random;
            $token->firstname=$request->firstname;
            $token->lastname=$request->lastname;
            $token->pnumber=$request->pnumber;
            $token->email=$request->email;
            $token->password=bcrypt($request->password);
            $token->save();
    
            Session::put('code',$random);    
            Session::put('n',$request->name);    
            Session::put('e',$request->email);    
    
    
            $maildata=$request->toArray();
             Mail::send('singin&singup.email.test_email_verify',$maildata,function($massage) use ($maildata)
             {
             $massage->to($maildata['email']); 
             $massage->subject('Test Email'); 
    
             });

           return redirect('/login')->with('verify',' Email  taken ');

           
    
    
    
            }
    
        }
        public function email_verify($email,$token){
            if( $data=verify_token::where('email',$email)->first()){
        
                if($data->token==$token && $data->email==$email){
        
                 $user_singup=new Singup();
                 $user_singup->firstname=$data->firstname;
                 $user_singup->lastname=$data->lastname;
                 $user_singup->pnumber=$data->pnumber;
                 $user_singup->email=$data->email;
                 $user_singup->password=bcrypt($data->password);
                 $user_singup->user_role='user';
                 $user_singup->save();
        
                 $id=verify_token::where('email',$email)->first();
                 $id->delete();
            
                return redirect('/login')->with('login',' Email  taken ');
        
            }
        
               }
        }

    public function singVerifyCode(Request $request){
        if($request->token==Session('code')){

            return redirect('/singup');
        }
        else{
            return "erorr";
        }
       
            
        }
 

        
    public function forgetEmailView(){
        return view('singin&singup.forget.enter_email');
                
        }
    public function forgetEmail(Request $request){
        $this->Validate($request, [
            'email' => 'required', 
         
       ]);
       if( $id=verify_token::where('email',$request->email)->first()){
        $id->delete();
       }
 
        
         
        $random= new verify_token();
         
        //  $random->name = $request->name;        
      
         $random->email = $request->email;
         $token=rand();
         $random->token = $token;
         $random->save();
 
         Session::put('code',$token);    
         Session::put('n',$request->name);    
          
         Session::put('e',$request->email);    
 
 
         $maildata=$request->toArray();
          Mail::send('singin&singup.forget.forget_email',$maildata,function($massage) use ($maildata)
          {
          $massage->to($maildata['email']); 
          $massage->subject('Test Email'); 
 
          });
         return redirect('/For-Email')->with('fogetemail',' Check Email ');
 
        
 
                    
        }
        public function forget_email_verify($email,$token){
            Session()->forget('email');
            Session()->forget('token');
            $data = verify_token::where('email',$email)
                                ->where('token',$token)
                                ->first();
            if($data){
               return view("singin&singup.forget.update_pass",['data'=>$data]);
            }
            else{
                return abort(404);
            }
        }
  
    public function forUpdatePass(Request $request){
        $this->Validate($request, [
            'email' => 'required', 
              
       ]);
   
        $update_pass=verify_token::where('token',$request->token)->where('email',$request->email )->first();
         
        if($update_pass){
           
            $data = Singup::where('email',$request->email)->first();
            $data->password=bcrypt($request->password);
            $data->save();
     
            
            $id=verify_token::where('email',$request->email)->first();
            $id->delete();
        
            return redirect('/login')->with('update','password changed');

          }
          else{
           return abort(404);
          }
       
 
                            
        }

    public function loginpage(){
        return view('singin&singup.singin.login');
    
        }
        public function user(){
            return "user Dashboard";
        }
        public function loginaction(Request $request){
            if($login_data=Singup::where('email',$request->email)->first()){
                if($login_data->email == $request->email && Hash::check($request->password,$login_data->password)){
                    $user_role=$login_data->user_role;
                    Session::put('email',$login_data->email);
                    Session::put('firstname',$login_data->firstname);
                    Session::put('lastname',$login_data->lastname);
                    Session::put('user_role',$user_role);
                   
        // return redirect('/dashboard')->with('signup','Password Successfully Changed');
        if($user_role=='admin'){
            return redirect('/dashboard');
        }
        elseif($user_role=='user'){
            return redirect('/user');
        }
                    
                }
                else{
                  
        return redirect('/login');

                    
                   
                }
    
            }
            else{
            return redirect('/login')->with('message',' Erorr ');
    
            }
        
            }
            public function logout(){
                

                session()->forget('email');
               session()->forget('firstname');
                session()->forget('lastname');
              
                return redirect('/login');

            }
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
    * @return \Illuminate\Http\Response
        */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('facebook')->user();
         $user->getName();
         $user->getEmail();
        

         $user_singup=new Singup();
         $user_singup->firstname=$user->getName();
         
         $user_singup->email=$user->getEmail();
         $user_singup->save();



      //   return $user->name;
      return  "success";
        
    }
// ************************************test****************************************************************

// public function testsing(){
//     return view('singin&singup.singup.test_singup');

// }
// public function testsing_post(Request $request){


//     if($checkemail=Singup::where('email',$request->email)->first()){
//         return redirect('/singemail')->with('taken',' Email  taken ');
//         //return "email_taken";
            


//         }
//         else{
//           if( $id=verify_token::where('email',$request->email)->first()){
//            $id->delete();
//           }
            
//        $token= new verify_token(); 

//         $random=rand();
//         $token->token = $random;
//         $token->firstname=$request->firstname;
//         $token->lastname=$request->lastname;
//         $token->pnumber=$request->pnumber;
//         $token->email=$request->email;
//         $token->password=bcrypt($request->password);
//         $token->save();

//         Session::put('code',$random);    
//         Session::put('n',$request->name);    
//         Session::put('e',$request->email);    


//         $maildata=$request->toArray();
//          Mail::send('singin&singup.email.test_email_verify',$maildata,function($massage) use ($maildata)
//          {
//          $massage->to($maildata['email']); 
//          $massage->subject('Test Email'); 

//          });
//        //  return redirect('/singToken')->with('check',' Check Email ');
//        return "check email";
       



//         }



        
// }
// public function email_verify($email,$token){
//     if( $data=verify_token::where('email',$email)->first()){

//         if($data->token==$token && $data->email==$email){

//          $user_singup=new Singup();
//          $user_singup->firstname=$data->firstname;
//          $user_singup->lastname=$data->lastname;
//          $user_singup->pnumber=$data->pnumber;
//          $user_singup->email=$data->email;
//          $user_singup->password=bcrypt($data->password);
//          $user_singup->save();

//          $id=verify_token::where('email',$email)->first();
//          $id->delete();
//          return "success";

//     }

//        }
// }
}
