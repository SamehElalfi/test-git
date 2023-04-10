<?php

namespace App\Http\Controllers;

use App\Models\Code_otp;
use App\Models\CsActivity;
use App\Models\Driver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Profile;
use App\Models\UserActivity;
use App\Models\User;


class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('user.index', compact('user'));
    }

    public function get_otp(Request $request){
        $current_date = date("Y-m-d H:i:s");
        $expire_date = date('Y-m-d H:i:s', strtotime($current_date. ' +3hours'));
        $random_code = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);

        require_once public_path("whatsapp.php");
        $otp_get = Code_otp::create([
            'user_id' => Auth::id(),
            'code' => $random_code,
            'expire_date' => $expire_date,
        ]);
        send("Your Code OTP-($random_code)");
    }

    public function otp(Request $request)
    {

        $current_date = date("Y-m-d H:i:s");
        $expire_date = date('Y-m-d H:i:s', strtotime($current_date. ' +3hours'));
        $random_code = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
        $code = $request->code;

        $otp_get = Code_otp::where('user_id', Auth::id())->where('expire_date', '>=', $current_date)->latest()->first();
        if ($otp_get == ""){
            $otp_get = Code_otp::create([
                'user_id' => Auth::id(),
                'code' => $random_code,
                'expire_date' => $expire_date,
            ]);
        }

        if ($code == $otp_get->code){
            $user = User::find(Auth::id());
            $user->phone_verfied_at = $current_date;
            $user->save();
            return redirect()->back()->with('success', 'تم تأكيد رقم الهاتف بنجاح');
        }else{
            return redirect()->back()->with('exist','الرمز السري خاطئ برجاء كتابة الرمز الصحيح');
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if (!isset($request->continue)){
            $this->validate($request, [
                'name' => ['required', 'string', 'max:255'],
                'password' => ['required'],
            ]);
            if (Auth()->user()->membership == 0){
                $this->validate($request, [
                    'fullname' => ['required', 'string', 'max:255'],
                    'gender' => ['required', 'string', 'max:255'],
                    'age' => ['required', 'string', 'max:99'],
                    'other_phone' => ['required', 'string'],
                    'relation' => ['required', 'string', 'max:255'],
                    'redirect' => ['required', 'string', 'max:255'],
                ]);
            }
            if (!Hash::check($request['password'], $user->password)) {
                return redirect()->back()->with('error', 'كلمة السر خاطئة');
            }
            if (isset($request->c_password)){
                $user->password = Hash::make($request->c_password);
            }
        }else{
            if (Auth()->user()->membership == 0){
                $this->validate($request, [
                    'fullname' => ['required', 'string', 'max:255'],
                    'gender' => ['required', 'string', 'max:255'],
                    'age' => ['required', 'string', 'max:99'],
                    'other_phone' => ['required', 'string'],
                    'relation' => ['required', 'string', 'max:255'],
                    'redirect' => ['required', 'string', 'max:255'],
                ]);
            }
            if (isset($user->profile)){
                return redirect('profile');
            }else{
                $user->profile = Profile::create([
                    "user_id" => $user->id,
                    "fullname" => $request->fullname ?? "0",
                    "gender" => $request->gender ?? "0",
                    "age" => $request->age ?? "0",
                    "other_phone" => $request->other_phone ?? "0",
                    "relation" => $request->relation ?? "0",
                    "redirect" => $request->redirect ?? "0",
                ]);
                return redirect()->back()->with('success', 'تم إستكمال الملف الشخصي بنجاح');
            }
        }
        if (!isset($user->profile)){
            $user->profile = Profile::create([
                "user_id" => $user->id,
                "fullname" => $request->fullname ?? "0",
                "gender" => $request->gender ?? "0",
                "age" => $request->age ?? "0",
                "other_phone" => $request->other_phone ?? "0",
                "relation" => $request->relation ?? "0",
                "redirect" => $request->redirect ?? "0",
            ]);
        }
        if (isset($request->photo)){
            $tmp = $_FILES['photo']['name'];
            $name_tmp = 'photo';
            $array = explode('.', $_FILES['photo']['name']);
            $thumb_width = 200;
            $thumb_height = 200;
            $path_page = 'users';
            require public_path('crop_image.php');
            $user->profile->photo = $NewImageName;
        }
        $user->name = $request->name ?? $user->name;
        $user->profile->fullname = $request->fullname ?? $user->profile->fullname;
        $user->profile->gender = $request->gender ?? $user->profile->gender;
        $user->profile->age = $request->age ?? $user->profile->age;
        $user->profile->other_phone = $request->other_phone ?? $user->profile->other_phone;
        $user->profile->relation = $request->relation ?? $user->profile->relation;
        $user->profile->redirect = $request->redirect ?? $user->profile->redirect;

        $user->profile->save();
        $user->save();
        return redirect()->back()->with('success', 'تم تعديل الملف الشخصي بنجاح');
    }

    public function info_user($id)
    {
        $user = User::find($id);
        return view('user.user_info', compact('user'));
    }

    public function analytics($id)
    {
        if ($id != Auth::id()){
            if (Auth::user()->membership < 3){
                return redirect()->back();
            }
        }
        $activities = UserActivity::all()->where('user_id', $id);
        return view('user.analytics', compact('activities'));
    }

    public function delete_user($id)
    {
        $user = User::find($id);
        if ($user->membership != 0){
            return redirect()->back();
        }
        $user->delete();

        //            Cs action
        $activity = CsActivity::create([
            "cs_id" => Auth()->id(),
            "user_id" => null,
            "driver_id" => null,
            "survey_id" => null,
            "option" => 8,
            "seen" => 0,
        ]);

        return redirect()->back();
    }

    public function block_user($id)
    {
        $user = User::find($id);
        if ($user->membership != 0){
            return redirect()->back();
        }
        $user->block = 1;
        $user->save();

        //            Cs action
        $activity = CsActivity::create([
            "cs_id" => Auth()->id(),
            "user_id" => $user->id,
            "driver_id" => null,
            "survey_id" => null,
            "option" => 4,
            "seen" => 0,
        ]);
        return redirect()->back();
    }

    public function remove_block_user($id)
    {
        $user = User::find($id);
        if ($user->membership != 0){
            return redirect()->back();
        }
        $user->block = 0;
        $user->save();

        //            Cs action
        $activity = CsActivity::create([
            "cs_id" => Auth()->id(),
            "user_id" => $user->id,
            "driver_id" => null,
            "survey_id" => null,
            "option" => 5,
            "seen" => 0,
        ]);

        return redirect()->back();
    }

    public function vip_user($id)
    {
        $user = User::find($id);
        if ($user->membership != 0){
            return redirect()->back();
        }
        $user->vip = 1;
        $user->save();

        //            Cs action
        $activity = CsActivity::create([
            "cs_id" => Auth()->id(),
            "user_id" => $user->id,
            "driver_id" => null,
            "survey_id" => null,
            "option" => 6,
            "seen" => 0,
        ]);

        return redirect()->back();
    }

    public function remove_vip_user($id)
    {
        $user = User::find($id);
        if ($user->membership != 0){
            return redirect()->back();
        }
        $user->vip = 0;
        $user->save();

        //            Cs action
        $activity = CsActivity::create([
            "cs_id" => Auth()->id(),
            "user_id" => $user->id,
            "driver_id" => null,
            "survey_id" => null,
            "option" => 7,
            "seen" => 0,
        ]);

        return redirect()->back();
    }

    public function add_client(Request $request)
    {
        return view('user.add_user');
    }

    public function store_client(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $users = User::where('email', $request->email)->orwhere('phone', $request->phone)->get();
        if (count($users) >= 1){
            return redirect()->back()->with('exist', 'هذه البيانات مسجلة بالفعل');
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email ?? $request->name.time(),
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        //            Cs action
        $activity = CsActivity::create([
            "cs_id" => Auth()->id(),
            "user_id" => $user->id,
            "driver_id" => null,
            "survey_id" => null,
            "option" => 3,
            "seen" => 0,
        ]);
        return redirect()->back()->with('success', 'تم إضافة عميل جديد');
    }

    public function add_user(Request $request)
    {
        return view('auth.add_user');
    }

    public function store_user(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required'],
            'membership' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $users = User::where('email', $request->email)->orwhere('phone', $request->phone)->get();
        if (count($users) >= 1){
            return redirect()->back()->with('exist', 'هذه البيانات مسجلة بالفعل');
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email ?? $request->name.time(),
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'membership' => $request->membership ?? 0,
        ]);
        return redirect()->back()->with('success', 'تم إضافة عميل جديد');
    }

    public function update_user(Request $request, $id)
    {
        $user = User::find($id);

        if (isset($request->c_password)){
            $user->password = Hash::make($request->c_password);
        }

        $user->name = $request->name ?? $user->name;
        if (isset($user->profile)){
            $user->profile->fullname = $request->fullname ?? $user->profile->fullname;
            $user->profile->gender = $request->gender ?? $user->profile->gender;
            $user->profile->age = $request->age ?? $user->profile->age;
            $user->profile->other_phone = $request->other_phone ?? $user->profile->other_phone;
            $user->profile->relation = $request->relation ?? $user->profile->relation;
            $user->profile->redirect = $request->redirect ?? $user->profile->redirect;
        }

//        Changing membership
        if (Auth()->user()->membership == 3 and in_array($request->membership, [0, 1, 2])){
            if ($user->membership != $request->membership){
                $user->membership = $request->membership;
                if ($user->membership == 0){
                    if (isset($user->driver)){
                        $user->driver->delete();
                    }
                }elseif ($user->membership == 1){
                    if (isset($user->profile)){
                        $user->profile->delete();
                    }
                    if (!isset($user->driver)){
                        Driver::create([
                            "user_id" => $user->id,
                            "fullname" => $user->name,
                            "bio" => "مرحبا انا سائق, ......",
                            "country_id" => 1,
                            "state_id" => 1,
                            "photo" => "user.png",
                        ]);
                    }
                }elseif ($user->membership == 2){
                    if (isset($user->driver)){
                        $user->driver->delete();
                    }
                    if (isset($user->profile)){
                        $user->profile->delete();
                    }
                }
            }
        }

        if (isset($user->profile)){
            $user->profile->save();
        }
        $user->save();
        return redirect()->back()->with('success', 'تم التعديل بنجاح');
    }
}
