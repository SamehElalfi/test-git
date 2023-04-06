<?php

namespace App\Http\Controllers;

use App\Models\CsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Car;
use App\Models\User;
use App\Models\Driver;
use App\Models\Survey;
use App\Models\UserActivity;
use App\Models\DriverActivity;
use Illuminate\Support\Facades\Hash;
use App\Imports\SurveyImport;
use Maatwebsite\Excel\Facades\Excel;
use Session;



class SurveyController extends Controller
{
    public function index()
    {
        $orders = Survey::where('user_id', Auth::id())->latest()->paginate(10);
        return view('quiz.index', compact('orders'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!isset($user->profile)) {
            return redirect('profile')->with('exist', 'برجاء إستكمال الملف الشخصي');
        }
        return view('quiz.create', compact('user'));
    }

    public function store(Request $request)
    {
        $user_id = Auth::id();
        $this->validate($request, [
            'terms' => ['required'],
            'reason' => ['required'],
            'needs' => ['required'],
            'date_tour' => ['required'],
            'time_tour' => ['required'],
            'start_point' => ['required'],
            'end_point' => ['required'],
            'address' => ['required', "min:10"],
        ]);
        if (isset($request->needs)) {
            $needs = [];
            foreach ($request->needs as $s_need) {
                $needs[] = $s_need;
            }
            $needs = implode(', ', $needs);
        }

        $order = Survey::create([
            "user_id" => $user_id,
            "driver_id" => null,
            "reason" => $request->reason ?? "غير محدد",
            "needs" => $needs ?? "ليس بحاجة لمساعد",
            "date_tour" => $request->date_tour ?? "فارغ",
            "time_date_tour" => $request->time_tour ?? "فارغ",
            "start_point" => $request->start_point ?? "فارغ",
            "end_point" => $request->end_point ?? "فارغ",
            "address" => $request->address ?? "فارغ",
            "notes" => $request->notes ?? "لا توجد",
            "distance_time" => 164,
            "distance_kilo" => 8,
            "status" => 0,
            "slug" => time(),
        ]);

        $activity = UserActivity::create([
            "user_id" => Auth::id(),
            "driver_id" => null,
            "survey_id" => $order->id,
            "option" => 0,
            "seen" => 1,
        ]);

        return redirect("quiz/show/$order->slug")->with('success', 'تم إرسال الطلب بنجاح');
    }

    public function edit($slug)
    {
        $user = Auth::user();
        $order = Survey::all()->where('slug', $slug)->first();
        if ($order == "" or $order->status > 0) {
            return redirect()->back();
        }
        if ($order->user_id == Auth::id() or Auth::user()->membership >=  2) {
            return view('quiz.edit', compact('order', 'user'));
        } else {
            return redirect()->back();
        }
    }

    public function change_status(Request $request, $id)
    {
        $status = $_POST['status'];
        $order = Survey::find($id);
        $order->status = $status;

//        Values of messages
        $slug = $order->slug ?? "غير محدد";
        $name = $order->user->name ?? "غير محدد";
        $phone = $order->user->phone ?? "غير محدد";
        $fullname = $order->user->profile->fullname ?? "غير محدد";
        $tour_date = $order->date_tour ?? "غير محدد";
        $tour_time = $order->time_date_tour ?? "غير محدد";
        $driver = $order->driver->fullname ?? "غير محدد";
        $car_num = $order->car->number ?? "غير محدد";
        $start_point = $order->start_point ?? "غير محدد";
        $end_point = $order->end_point ?? "غير محدد";
        $cost = 250;

        if ($status == 1) {
            $this->validate($request, [
                "driver" => "required"
            ]);
            require_once public_path('msg_status.php');
            require_once public_path('whatsapp.php');
            $order->driver_id = $request->driver;
            $order->car_id = $request->car;

            //            Message to the client
            $message = awaits_user($slug, $fullname, $tour_date, $tour_time, $driver, $car_num, $start_point, $end_point, $cost);
            send($message);
            $activity = UserActivity::create([
                "user_id" => $order->user_id,
                "driver_id" => $order->driver_id,
                "survey_id" => $order->id,
                "option" => 2,
                "seen" => 0,
            ]);

            //            Message to the driver
            $message = awaits_driver($slug, $name, $fullname, $phone, $start_point, $end_point, $cost);
            send($message);
            $activity = DriverActivity::create([
                "user_id" => $order->user_id,
                "driver_id" => $order->driver_id,
                "survey_id" => $order->id,
                "option" => 0,
                "seen" => 0,
            ]);

//            Cs action
            $activity = CsActivity::create([
                "cs_id" => Auth()->id(),
                "user_id" => $order->user_id,
                "driver_id" => $order->driver_id,
                "survey_id" => $order->id,
                "option" => 0,
                "seen" => 0,
            ]);

            $order->save();
            return redirect()->back()->with('success', "تمت معالجة الطلب \n وإرسال رسالة تأكيد الي العميل و الكابتن بنجاح");
        }elseif ($status == 2){
            $activity = DriverActivity::create([
                "user_id" => $order->user_id,
                "driver_id" => $order->driver_id,
                "survey_id" => $order->id,
                "option" => 3,
                "seen" => 0,
            ]);
            $activity = UserActivity::create([
                "user_id" => $order->user_id,
                "driver_id" => $order->driver_id,
                "survey_id" => $order->id,
                "option" => 5,
                "seen" => 0,
            ]);
        }elseif ($status == 3){
            $activity = DriverActivity::create([
                "user_id" => $order->user_id,
                "driver_id" => $order->driver_id,
                "survey_id" => $order->id,
                "option" => 2,
                "seen" => 0,
            ]);
            $activity = UserActivity::create([
                "user_id" => $order->user_id,
                "driver_id" => $order->driver_id,
                "survey_id" => $order->id,
                "option" => 4,
                "seen" => 0,
            ]);

            //            Cs action
            $activity = CsActivity::create([
                "cs_id" => Auth()->id(),
                "user_id" => $order->user_id,
                "driver_id" => $order->driver_id,
                "survey_id" => $order->id,
                "option" => 2,
                "seen" => 0,
            ]);
        }elseif ($status == 4){
            $activity = DriverActivity::create([
                "user_id" => $order->user_id,
                "driver_id" => $order->driver_id ?? null,
                "survey_id" => $order->id,
                "option" => 1,
                "seen" => 0,
            ]);
            $activity = UserActivity::create([
                "user_id" => $order->user_id,
                "driver_id" => $order->driver_id ?? null,
                "survey_id" => $order->id,
                "option" => 1,
                "seen" => 0,
            ]);

            //            Cs action
            $activity = CsActivity::create([
                "cs_id" => Auth()->id(),
                "user_id" => $order->user_id,
                "driver_id" => $order->driver_id,
                "survey_id" => $order->id,
                "option" => 1,
                "seen" => 0,
            ]);
        }

        $order->save();
        return redirect()->back()->with('success', 'تمت معالجة الطلب بنجاح');
    }

    public function show($slug)
    {
        $user = Auth::user();
        $order = Survey::all()->where('slug', $slug)->first();
        if ($order == "") {
            return redirect()->back();
        }
        if ($order->user_id == Auth::id() or Auth::user()->membership >=  1) {
            return view('quiz.show', compact('order', 'user'));
        } else {
            return redirect()->back();
        }
    }

    public function process($id)
    {
        $order = Survey::find($id);
        $users = User::all()->where('membership', 1);
        $cars = Car::all();
        if ($order == "") {
            return redirect()->back();
        }

        return view('quiz.process', compact('order', 'users', 'cars'));
    }

    public function update(Request $request, $slug)
    {
        $user_id = Auth::id();
        $order = Survey::find(Survey::all()->where('slug', $slug)->first()->id);
        if ($order->status > 0){
            return redirect()->back();
        }

        $this->validate($request, [
            'reason' => ['required'],
            'needs' => ['required'],
            'date_tour' => ['required'],
            'time_tour' => ['required'],
            'start_point' => ['required'],
            'end_point' => ['required'],
            'address' => ['required', "min:10"],
        ]);
        if (isset($request->needs)) {
            $needs = [];
            foreach ($request->needs as $s_need) {
                $needs[] = $s_need;
            }
            $needs = implode(', ', $needs);
        }

        $order->reason = $request->reason ?? $order->reason;
        $order->needs = $needs ?? $order->needs;
        $order->date_tour = $request->date_tour ?? $order->date_tour;
        $order->time_date_tour = $request->time_tour ?? $order->time_date_tour;
        $order->start_point = $request->start_point ?? $order->start_point;
        $order->end_point = $request->end_point ?? $order->end_point;
        $order->address = $request->address ?? $order->address;
        $order->notes = $request->notes ?? $order->notes;
        $order->save();

        return redirect("quiz/show/$order->slug")->with('success', 'تم تعديل البيانات بنجاح');
    }

    public function add_multiple()
    {
        $users = User::all();
        return view('quiz.add_multiple', compact('users'));
    }

    public function store_multiple(Request $request)
    {
        Excel::import(new SurveyImport(), $request->file('xlsx'));

        //            Cs action
        $activity = CsActivity::create([
            "cs_id" => Auth()->id(),
            "user_id" => null,
            "driver_id" => null,
            "survey_id" => null,
            "option" => 4,
            "seen" => 0,
        ]);

        return redirect()->back()->with('success', 'تم إنشاء الرحلات بنجاح');
    }

    public function add_solid()
    {
        $users = User::all();
        return view('quiz.add_solid', compact('users'));
    }

    public function store_solid(Request $request)
    {
        if ($request->op_user == 0){
            $user_id = $request->user_id;
            $find_user = User::find($user_id);
            if ($find_user == ""){
                return redirect()->back()->with('error', 'المستخدم غير موجود');
            }
        }elseif ($request->op_user == 1){
            $this->validate($request, [
                'username' => ['required'],
                'email' => ['required'],
                'phone' => ['required'],
                'password' => ['required'],
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
            $user_id = $user->id;
        }
        $this->validate($request, [
            'terms' => ['required'],
            'reason' => ['required'],
            'needs' => ['required'],
            'date_tour' => ['required'],
            'time_tour' => ['required'],
            'start_point' => ['required'],
            'end_point' => ['required'],
            'address' => ['required', "min:10"],
        ]);
        if (isset($request->needs)) {
            $needs = [];
            foreach ($request->needs as $s_need) {
                $needs[] = $s_need;
            }
            $needs = implode(', ', $needs);
        }

        $order = Survey::create([
            "user_id" => $user_id,
            "driver_id" => null,
            "reason" => $request->reason ?? "غير محدد",
            "needs" => $needs ?? "ليس بحاجة لمساعد",
            "date_tour" => $request->date_tour ?? "فارغ",
            "time_date_tour" => $request->time_tour ?? "فارغ",
            "start_point" => $request->start_point ?? "فارغ",
            "end_point" => $request->end_point ?? "فارغ",
            "address" => $request->address ?? "فارغ",
            "notes" => $request->notes ?? "لا توجد",
            "distance_time" => 164,
            "distance_kilo" => 8,
            "status" => 0,
            "slug" => time(),
        ]);

//        $activity = UserActivity::create([
//            "user_id" => Auth::id(),
//            "driver_id" => null,
//            "survey_id" => $order->id,
//            "option" => 0,
//            "seen" => 0,
//        ]);

        //            Cs action
        $activity = CsActivity::create([
            "cs_id" => Auth()->id(),
            "user_id" => $order->user_id,
            "driver_id" => null,
            "survey_id" => $order->id,
            "option" => 3,
            "seen" => 0,
        ]);

        return redirect("quiz/show/$order->slug")->with('success', 'تم إرسال الطلب بنجاح');
    }
}
