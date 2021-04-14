<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\{User,StateModel,CityModel,UserImageModel};

use Auth;
use Intervention\Image\ImageManagerStatic as Image;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['getState','getCity']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getUser = Auth::user();
      //  print_r($getUser);exit;
        return view('home');
    }

    public function getState()
    {
        $getState = StateModel::where('country_id', '101')->get();

        $html='';

        if(sizeof($getState) > 0)
        {
            $html='<option value="">Select</option>';

             foreach($getState as $state_list)
             {
                $html.='<option value="'.$state_list->id.'">'.$state_list->name.'</option>';
             }
        }

        return response()->json(['html'=>$html]);
    }


    public function getCity(Request $request)
    {
        $getStateId = $request->id;

        $city_id = $request->city_id;

        $getCity = CityModel::where('state_id', $getStateId)->get();

        $html='';

         $html='<option value="">Select</option>';
        if(sizeof($getCity) > 0)
        {
             foreach($getCity as $city_list)
             {
                $html.='<option value="'.$city_list->id.'"';
                    if(isset($city_id) && $city_id == $city_list->id)
                    {
                      $html.=' selected=""';
                    }
                $html.='>'.$city_list->name.'</option>';
             }
        }

        return response()->json(['html'=>$html]);

    }

    public function myprofile()
    {
        $getUser = Auth::user();

        $getState = StateModel::where('country_id', '101')->get();

        $getUserImage = UserImageModel::where('user_id', $getUser->id)->where('is_default', 'no')->get();

        $deafultImage = UserImageModel::where('user_id', $getUser->id)->where('is_default', 'yes')->first();

        return view('my-profile', ['getUser'=>$getUser, 'getState'=>$getState, 'userImages'=>$getUserImage, 'deafultImage'=>$deafultImage]);
    }

    public function updateUser(Request $requests)
    {

        $data = $requests->all();

        $user_id = Auth::user()->id;
        $user_email = Auth::user()->email;

        $unique = '|unique:users';
        if($user_email == $data['email'])
        {
            $unique ='';
        }

        $validator  =  Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255'.$unique,
            'address' =>'required',
            'state' => 'required',
            'city' => 'required',                
            'subject' =>'required',            

        ]);

        if ($validator->fails())
        {
            $messages = $validator->messages();
            
            return redirect()->back()->withInput($requests->all())->withErrors($messages);
        }
 
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

            if(sizeof($image_insert_array) > 0)
              {   
                     
                 foreach($image_insert_array as $key=>$val)
                 {  
                   
                    UserImageModel::create(['user_id'=>$user_id, 'image'=>$val]);
                 }
              }

        }

       
        $user = User::where('id', $user_id)->update([

                        'first_name' => $data['first_name'],
                        'last_name' => $data['last_name'],
                        'email' => $data['email'],
                        'address' => $data['address'],
                        'state' => $data['state'],
                        'city' => $data['city'],            
                        'subject' => $data['subject'],
                    ]);

        

        return redirect('/myprofile')->with('success', 'Data updated successfully!');
    }


    public function deleteDefaulytImage(Request $requests)
    {
        $data = $requests->all();

         //delete previous photo
        $getOldImage = UserImageModel::where('id', $data['id'])->first();

        if(sizeof($getOldImage)>0 && is_file(storage_path('/app/public/images/').$getOldImage->image))
        {    
            unlink(storage_path('/app/public/images/').$getOldImage->image); 
        }

        UserImageModel::where('id', $getOldImage->id)->delete();
        

        return redirect('/myprofile')->with('success', 'Default image deleted!');

    }   

    public function deleteImage(Request $requests)
    {
        $data = $requests->all();

         //delete previous photo
        $getOldImage = UserImageModel::where('id', $data['id'])->first();

        if(sizeof($getOldImage)>0 && is_file(storage_path('/app/public/images/').$getOldImage->image))
        {    
            unlink(storage_path('/app/public/images/').$getOldImage->image); 
        }

        UserImageModel::where('id', $getOldImage->id)->delete();
        

        return response()->json(['success'=>true]);

    }   

    public function uploadDefaulytImage(Request $requests)
    {   

        $user_id = Auth::user()->id;

        if($requests->hasFile('image'))
        {
          
            $image_file = $requests->image;
            
            $extension = $image_file->getClientOriginalExtension();

             if($extension == 'jpg' || $extension == 'JPG' || $extension == 'jpeg' || $extension == 'JPEG' || $extension == 'png' || $extension == 'PNG' || $extension == 'gif' || $extension == 'GIF')
             {

                $image       = $image_file;
                $filename    = date('YmdHis').'_'.$image->getClientOriginalName();

                $image_resize = Image::make($image->getRealPath());              
                $image_resize->resize(400, 300);
                $image_resize->save(storage_path('app/public/images/' .$filename));

                 //delete previous photo
                $getOldImage = UserImageModel::where('user_id', $user_id)->where('is_default', 'yes')->first();

                if(sizeof($getOldImage)>0 && is_file(storage_path('/app/public/images/').$getOldImage->image))
                {
                   unlink(storage_path('/app/public/images/').$getOldImage->image); 
                }

                UserImageModel::updateOrCreate(['user_id'=>$user_id, 'is_default'=>'yes'],['image'=>$filename]);

                return response()->json(['success'=>true]);
              
             }
             else
             {
                return response()->json(['success'=>false, 'msg'=>'Select a valid image file']);
             }
              
            
        }
        else
        {   
            return response()->json(['success'=>false, 'msg'=>'Please upload a image']);
           
        }        

    }



}
