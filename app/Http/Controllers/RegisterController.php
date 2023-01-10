<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use App\Models\Customer;
use \App\Mail\SendMail;
use Session;
use DB;

class RegisterController extends Controller
{
    public function __construct()
	{  
		if(Session::get('login_id') > 0)
		{  
			return redirect('/dashboard');
		} else { 
            return redirect('/');
        }
	}
    public function index()
	{   
        if(Session::get('login_id') > 0)
		{  
			return redirect('/dashboard');
		} 
        return view('index');
    }
    public function customersignup()
	{   
        return view('customersingup');
    }
    public function customeradd(Request $request)
    {
        $validatedata = $request->validate(
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'dob' => 'required',
                'password' => 'required|min:5'
            ]
        );
        $getUserID =   DB::table('customers')->where('email', $request->email)->where('type', '2')->where('status', '1')->first();
        $id = isset($getUserID) ? $getUserID->id: 0;
        if ($id == 0) {
            $insert = [];
            $insert['first_name'] = $request->first_name;
            $insert['last_name'] = $request->last_name;
            $insert['email'] = $request->email;
            $insert['dob'] = $request->dob;
            $insert['password'] = md5($request->password);
            $insert['type'] = '2';
            $insert['created_at'] = date('Y-m-d H:i:s');
            DB::table('customers')->insert($insert); 
            Session::flash('message','Customer account crreated. Please wait sometime for your details apporve from our admin');
            Session::flash('class','success');
            return back();
        } else {
            Session::flash('message','Email ID already exist');
            Session::flash('class','error');
            return back();  
        } 
            
    }
    public function adminsignup()
	{   
        return view('adminsignup');
    }
    public function adminadd(Request $request)
    {
        $validatedata = $request->validate(
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'dob' => 'required',
                'password' => 'required|min:5'
            ]
        );
        $getUserID =   DB::table('customers')->where('email', $request->email)->where('type', '1')->where('status', '1')->first();
        $id = isset($getUserID) ? $getUserID->id: 0;
        if ($id == 0) {
            $insert = [];
            $insert['first_name'] = $request->first_name;
            $insert['last_name'] = $request->last_name;
            $insert['email'] = $request->email;
            $insert['dob'] = $request->dob;
            $insert['password'] = md5($request->password);
            $insert['type'] = '1';
            $insert['status'] ='1';
            $insert['created_at'] = date('Y-m-d H:i:s');
            DB::table('customers')->insert($insert); 
            Session::flash('message','Admin account created. Please login now');
            Session::flash('class','success');
            return redirect('/');
        } else {
            Session::flash('message','Email ID already exist');
            Session::flash('class','error');
            return back();  
        }     
    }
    public function signin(Request $request)
    {
        $validatedata = $request->validate(
            [
                'username' => 'required',
                'password' => 'required'
            ]
        );
        $getUserID =   DB::table('customers')->where('email', $request->username)->where('password', md5($request->password))->where('type', '1')->where('status', '1')->first();
        $id = isset($getUserID) ? $getUserID->id: 0;
        if ($id > 0 ) {
            $username = $getUserID->first_name;
            $type = 'admin';
        } else {
            $getUserID =   DB::table('customers')->where('email', $request->username)->where('password', md5($request->password))->where('type', '2')->where('status', '1')->first();
            $id = isset($getUserID) ? $getUserID->id: 0;
            if ($id > 0) {
                $username = $getUserID->first_name;
                $type = 'user';
            }
        }
        if ($id > 0 ) {
            Session::put('login_id', $id);	
            Session::put('login_firstname', $getUserID->first_name);
            Session::put('login_lastname', $getUserID->last_name);
            Session::put('login_type', $type);
            return redirect('/dashboard');
        } else {
            Session::flash('message','Username and password are mismatch');
            Session::flash('class','error');
            return back();  
        }
        
        
            
    }
    public function dashboard()
    {
        if(Session::get('login_id') > 0)
		{  
			$customers = DB::table('customers')->where('type', '2')->orderby('id','desc')->paginate(10);
            return view('dashboard',compact('customers'));
		} else { 
            return redirect('/');
        }
        
    }
    public function logout()
    {
        session()->forget('login_id');
		session()->forget('login_firstname');
		session()->forget('login_lastname');
		session()->forget('login_type');
		return redirect('/');
    }
    public function approve($id)
    {
        $update = [];
        $update['status'] = '1';
        DB::table('customers')->where('id', $id)->update($update); 
        $details = [
            'title' => 'Customer form status',
            'body' => 'Hi, your form is approved by our admin. Please go to our portal and log in. Thank you'
        ];
       
        \Mail::to('yabase125@gmail.com')->send(new SendMail($details));
        Session::flash('message','Customer approved successfully');
        Session::flash('class','success');
        return back();
    }
    public function reject($id)
    {
        $update = [];
        $update['status'] = '2';
        DB::table('customers')->where('id', $id)->update($update); 
        $details = [
            'title' => 'Customer form status',
            'body' => 'Hi, your form is rejected by our admin.'
        ];
       
        \Mail::to('yabase125@gmail.com')->send(new SendMail($details));
        Session::flash('message','Customer rejected successfully');
        Session::flash('class','success');
        return back();
    }
    public function testMail()
    {
        $details = [
            'title' => 'Customer form status',
            'body' => 'Hi, your form is approved by our admin. Please go to our portal and log in. Thank you'
        ];
       
        \Mail::to('yabase125@gmail.com')->send(new SendMail($details));
          
    }
}
