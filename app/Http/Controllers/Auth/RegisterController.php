<?php

namespace App\Http\Controllers\Auth;

use App\{User,UserImageModel};
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;

use Intervention\Image\ImageManagerStatic as Image;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'address' =>'required',
            'state' =>'required',
            'city' =>'required',
            'images' => 'required|mimes:jpeg,JPEG,png,PNG,jpg,JPG,gif,GIF',
            'subject' =>'required',

            'password' => 'required|string|min:6|confirmed',

        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function registrationProcess(Request $requests)
    {
        
        $data = $requests->all();

        $validator  =  Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'address' =>'required',   
            'state' => 'required',
            'city' => 'required',          
            'subject' =>'required',

            'password' => 'required|string|min:6|confirmed',

        ]);

        if ($validator->fails())
        {
            $messages = $validator->messages();
            
            return redirect()->back()->withInput($requests->all())->withErrors($messages);
        }
        
            //print_r($data); exit;

        if($requests->hasFile('images'))
        {
            $image_insert_array=array();
            foreach($requests->images as $image_file)
            {
                $extension = $image_file->getClientOriginalExtension();

                 if($extension == 'jpg' || $extension == 'JPG' || $extension == 'jpeg' || $extension == 'JPEG' || $extension == 'png' || $extension == 'PNG' || $extension == 'gif' || $extension == 'GIF')
                 {

                    $image       = $image_file;
                    $filename    = date('YmdHis').'_'.$image->getClientOriginalName();

                    $image_resize = Image::make($image->getRealPath());              
                    $image_resize->resize(400, 300);
                    $image_resize->save(storage_path('app/public/images/' .$filename));

                    $image_insert_array[]=$filename;
                 }
                 else
                 {
                    return redirect()->back()->withInput($requests->all())->withErrors(['image'=>'Select a valid image file']);
                 }
              
            }
        }
        else
        {
            return redirect()->back()->withInput($requests->all())->withErrors(['image'=>'Image field is requred']);
        }

       // print_r($image_insert_array); exit;
        

          $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'address' => $data['address'],
                'state' => $data['state'],
                'city' => $data['city'],            
                'subject' => $data['subject'],
                'password' => bcrypt($data['password']),
            ]);

          
          if(sizeof($image_insert_array) > 0)
          {   
                 
             foreach($image_insert_array as $key=>$val)
             {  
                $default_value='no'; 
                if($key == 0)
                {
                  $default_value='yes';  
                }

                UserImageModel::create(['user_id'=>$user->id, 'image'=>$val, 'is_default'=>$default_value]);
             }
          }

      
        $credentials = $requests->only('email', 'password');


        //login current user

        Auth::login($user); 
       
        return redirect()->intended('/home');
      
      

   

    }
}
