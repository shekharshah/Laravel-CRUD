<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Car;
use App\Hobby;
use App\Images;
use Validator;
use Yajra\Datatables\Datatables;
use Session;

class YajraController extends Controller
{
    public function index(Request $request)
    {
        return view('index');
    }

    public function datatable(Request $request)
    {
        $users = User::with(['car','hobby','image']);
        // dd($users);
        return Datatables::of($users)
            // ->addColumn('check', '<input type="checkbox" class="checkbox" name="selected_users[]" value="{{ $id }}">')

            ->addColumn('language',function($r){
                return ($r->language != null) ? $r->language : "None";
            })
            // ->editColumn('status',function($rr){
            //     if($rr->status == '1'){
            //         return '<button class="btn btn-success btn-sm">Active</button>';
            //     }
            //     else if($rr->status == '0'){
            //         return '<button class="btn btn-danger btn-sm">Non-Active</button>';
            //     }
            //     else{
            //         return "None";
            //     }
            // })
            ->addColumn('actions', function ($action) {
                return '<a href="'.url('view_profile/'.$action->id).'" class="btn btn-xs btn-info"><i class="glyphicon glyphicon-open"></i> View</a>'.'  '.'<a href="'.url('edit/'.$action->id).'" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-edit"></i> Edit</a>'.' '.'<a href="javascript:void(0)" link="'.route('delete_record',$action->id).'" class="btn btn-xs btn-danger delete"><i class="glyphicon glyphicon-trash"></i> Delete</a>' ;
            })
            ->addColumn('cars',function ($r){
                if(!$r->car->isEmpty())
                {
                    $data = "";
                    foreach($r->car as $key => $value){
                        if($r->car->keys()->last() == $key){
                            $app = "";
                        }
                        else{
                            $app = ", ";
                        }   
                        $data .=$value->car_name.''.$app;
                    }
                }
                else
                {
                    $data="Null";
                }
                return $data;
            }) 
            ->addColumn('hobbies',function ($r){
                if(!$r->hobby->isEmpty())
                {
                    $data = "";
                    foreach($r->hobby as $key => $value){
                        if($r->hobby->keys()->last() == $key){
                            $app = "";
                        }
                        else{
                            $app = ", ";
                        }   
                        $data .=$value->hobby.''.$app;
                    }
                }
                else
                {
                    $data="Null";
                }
                return $data;
            })
            ->addColumn('images',function($file){
               
                if(!$file->image->isEmpty())
                {
                    $data = "";
                    foreach($file->image as $key => $value){
                        if($file->image->keys()->last() == $key){
                            $app = "";
                        }
                        else{
                            $app = "&nbsp;";
                        }
                        $url = url("images/".$value->imagefile);
                        $data .='<img src="'. $url .'" height="50px" />'.''.$app;
                    }
                }
                else
                {
                    $data="No Image";
                }
                return $data;
            })
            ->filter(function ($query) use ($request) { //custom search
                if (!empty($request['name'])) {
                    $query->where('name', 'like', "%" . $request['name'] . "%");
                }

            }, true) 
            ->rawColumns(['language','cars','hobbies','actions','images'])
            ->make(true);
            // ->order(function ($query) {
            //     if (request()->has('name')) {
            //         $query->orderBy('name', 'asc');
            //     }
            // })
    }       

    public function create()
    {
        return view('register');
    }

    public function check_name_exists(Request $t)
    {
        $user = User::where('name', $t->name)->first();
        if($user == null) 
        {
            return "true";
        } 
        else
        {
            return "false";
        }
    }
    // public function image_store(Request $request)
    // {
        // $info = new User;//model class is defined
    //     $images = $request->file('image');
    //     $input['image'] = time().'.'.$images->getClientOriginalExtension();
    //     $destinationPath = public_path('/images');
    //     $images->move($destinationPath, $input['image']);
    //     $info->image = $input['image'];
    // }
    public function store(Request $request)
    {
        // dd($request->all());exit;
        $this->validate($request, [
            'name' => 'required|min:3|max:20',
            'email' => 'required',
            'password' => 'required|min:6',
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif',
        ],
        [
            'name.required'=>'Please enter your name',
            'name.min'=>'Name should not be less than 3 characters',
            'name.max'=>'Name should not be more than 20 characters',
            'password.min'=>'Password should not be less than 6 characters',
            'image.*'=>'Only image file can be uploaded'
        ]);
        //user
        $info = new User;//model class is defined
        $info->name =$request->name;
        $info->email =$request->email;
        $info->password =\Hash::make($request->password);
        if(isset($request->language) && !empty($request->language)){
            
            foreach ($request->language as $key => $value) {

                $info->language =implode(",",$request->language);
            }
        }
        else{

            $info->language = "Null";
        }
        $info->status =$request->status;
        $info->save();

        // $images = $request->file('image');
        // $input['image'] = time().'.'.$images->getClientOriginalExtension();
        // $destinationPath = public_path('/images');
        // $images->move($destinationPath, $input['image']);
        // $info->image = $input['image'];
        // $info->save();
        
        //images
        if($request->hasfile('image')){

            // dd($request->file('image'));
            foreach ($request->file('image') as $file) {

                $infoimage = new Images;//model class is defined
                $images = $file;
                $input['image'] = time().'.'.$images->getClientOriginalExtension();
                $destinationPath = public_path('/images');
                $images->move($destinationPath, $input['image']);
                $infoimage->user_id = $info->id;
                $infoimage->imagefile = $input['image'];
                $infoimage->save();
            }
        }

        //car
        if(isset($request->car_name) && !empty($request->car_name)){
            
            foreach ($request->car_name as $key => $value) {

                $infocar = new Car;
                $infocar->user_id = $info->id;
                $infocar->car_name =$value;
                $infocar->save();
            }
        }
        else{

            $infocar = new Car;
            $infocar->user_id = $info->id;
            $infocar->car_name = "Null";
            $infocar->save();
        }

        //hobby
        if(isset($request->hobby) && !empty($request->hobby)){
            
            foreach ($request->hobby as $hobbies) {

                $hobby = new Hobby;
                $hobby->user_id = $info->id;
                $hobby->hobby =$hobbies;
                $hobby->save(); 
            }
        }
        else{

            $hobby = new Hobby;
            $hobby->user_id = $info->id;
            $hobby->hobby ="Null";
            $hobby->save(); 
        }
        session()->flash('Success','Record Added Successfully...!!');
        return redirect()->route('index');
    }

    public function view_record($id)
    {
        $infodata=User::with(["car","hobby","image"])->find($id);
        return view('view',['infodata'=>$infodata]);
    }

	public function edit($id)
    {
        $infodata=User::with(["car","hobby"])->find($id);
        $language =explode(",",$infodata->language);
        return view('edit',['infodata'=>$infodata,'language'=>$language]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:20',
        ],
        [
            'name.required'=>'Please enter your name',
            'name.min'=>'Name should not be less than 3 characters',
            'name.max'=>'Name should not be more than 20 characters',
        ]);
        
        $car = Car::where('user_id',$id)->delete();
        
        $hobby = Hobby::where('user_id',$id)->delete();
        
        $info = User::find($id);
        $info->name =$request->get('name');
        if(isset($request->language) && !empty($request->language)){
            
            foreach ($request->language as $key => $value) {

                $info->language =implode(",",$request->language);
            }
        }
        else{

            $info->language = "Null";
        }
        $info->save();

        // dd($request->all());
        if(isset($request->car_name) && !empty($request->car_name)){
            
            foreach ($request->car_name as $key => $value){
                
                $infocar = new Car;
                $infocar->user_id = $info->id;
                $infocar->car_name =$value;
                $infocar->save();
            }
        }
        else{

            $infocar = new Car;
            $infocar->user_id = $info->id;
            $infocar->car_name = "Null";
            $infocar->save();
        }

        if(isset($request->hobby) && !empty($request->hobby)){
            
            foreach ($request->hobby as $hobbies) {

                    $hobby = new Hobby;
                    $hobby->user_id = $info->id;
                    $hobby->hobby =$hobbies;
                    $hobby->save(); 
            }
        }
        else{

            $hobby = new Hobby;
            $hobby->user_id = $info->id;
            $hobby->hobby ="Null";
            $hobby->save(); 
        }
       
        session()->flash('msg-update','Record Updated Successfully...!!');
        return redirect()->route('index');
    }

 	public function destroy($id)
    {
        $infodelete =User::find($id);
        $infodelete->delete();
        session()->flash('msg-delete','Record Deleted Successfully...!!');
        return redirect()->route('index');
    }
}
