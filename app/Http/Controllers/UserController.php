<?php

namespace App\Http\Controllers;

use App\Models\Code_otp;
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
                'fullname' => ['required', 'string', 'max:255'],
                'gender' => ['required', 'string', 'max:255'],
                'age' => ['required', 'string', 'max:99'],
                'other_phone' => ['required', 'string'],
                'relation' => ['required', 'string', 'max:255'],
                'redirect' => ['required', 'string', 'max:255'],
                'password' => ['required'],
            ]);
            if (!Hash::check($request['password'], $user->password)) {
                return redirect()->back()->with('error', 'كلمة السر خاطئة');
            }
            if (isset($request->c_password)){
                $user->password = Hash::make($request->c_password);
            }
        }else{
            $this->validate($request, [
                'fullname' => ['required', 'string', 'max:255'],
                'gender' => ['required', 'string', 'max:10'],
                'age' => ['required', 'numeric', 'max:99'],
                'other_phone' => ['required', 'numeric'],
                'relation' => ['required', 'string', 'max:255'],
                'redirect' => ['required', 'string', 'max:255'],
            ]);
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

        $user->profile->save();
        $user->save();
        return redirect()->back()->with('success', 'تم التعديل بنجاح');
    }
}
