<?php

namespace App\Http\Controllers;

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
        if ($order == "") {
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
        if ($status == 1) {
            $this->validate($request, [
                "driver" => "required"
            ]);
            require_once public_path('whatsapp.php');
            $order->driver_id = $request->driver;
            $order->car_id = $request->car;

            //            Message to the client
            $message = "
            شكرا لك لاختيارك يسر, لقد تم تأكيد الرحلة وبإنتظار التحرك في الوقت المحدد من طرفكم ان شاء الله.
            \n
            تفاصيل الرحلة:
            - رقم الرحلة:- #" . ($order->slug) . "
            - اسم المستفيد:- " . ($order->user->profile->fullname ?? "") . "
            - موعد بدء الرحلة:- " . ($order->date_tour . " | " . $order->time_date_tour) . "
            - اسم الكابتن:- " . ($order->driver->fullname ?? "غير محدد") . "
            - رقم السيارة:- " . ($order->car->number) . "
            - مركز الانطلاق:- " . ($order->start_point) . "
            - مركز الوصول:- " . ($order->end_point) . "
            - التكلفة المتوقعة:- " . (250) . " ريال
            \n
            نتمني لكم رحلة مريحة - مع دوام الصحة و العافية ان شاء الله
            ";
            send($message);
            $message = "
            لديك رحلة في انتظار التحرك ان شاء الله, وتفاصيلها كالاتي:-
            \n
             - رقم الرحلة:- #" . ($order->slug) . "
            -اسم العميل: " . ($order->user->name ?? "غير محدد") . "
            -اسم المستفيد: " . ($order->user->profile->fullname ?? "غير محدد") . "
            -رقم جوال العميل: " . ($order->user->phone ?? "غير محدد") . "
            -مركز الانطلاق: " . ($order->start_point ?? "غير محدد") . "
            -مركز الوصول: " . ($order->end_point ?? "غير محدد") . "
            -التكلفة المتوقعة: " . (250) . " ريال
            ";
            send($message);
            $order->save();
            return redirect()->back()->with('success', "تمت معالجة الطلب \n وإرسال رسالة تأكيد الي العميل و الكابتن بنجاح");
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

    public function confirm($id, Request $request)
    {
        $driver_id = $request->driver;
        $order = Survey::find($id);
        $driver = Driver::find($driver_id);
        if ($order == "") {
            return redirect()->back();
        } else {
            if ($order->status != 0) {
                return redirect()->back();
            }
        }
        if ($driver == "") {
            return redirect()->back()->with('exist', 'هناك مشكلة في معالجة الرحلة لهذا السائق');
        }

        $order->driver_id = $driver_id;
        $order->status = 1;
        $driver->status = 0;
        $order->save();
        $driver->save();

        $activity = UserActivity::create([
            "user_id" => $order->user_id,
            "driver_id" => $driver->id,
            "survey_id" => $order->id,
            "option" => 2,
            "seen" => 0,
        ]);
        $activity = DriverActivity::create([
            "user_id" => $order->user_id,
            "driver_id" => $driver->id,
            "survey_id" => $order->id,
            "option" => 0,
            "seen" => 0,
        ]);

        return redirect("quiz/show/$order->slug");
    }

    public function update(Request $request, $slug)
    {
        $user_id = Auth::id();
        $order = Survey::find(Survey::all()->where('slug', $slug)->first()->id);
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

    public function cancel($id)
    {
        $order = Survey::find($id);

        if ($order == "") {
            return redirect()->back();
        } elseif (Auth::user()->membership == 0) {
            if ($order->user_id != Auth::id()) {
                return redirect()->back();
            }
        } elseif (Auth::user()->membership == 1) {
            if (!isset(Auth::user()->driver)) {
                return redirect()->back();
            } else {
                if ($order->driver_id != Auth::user()->driver->id) {
                    return redirect()->back();
                }
            }
        } elseif (Auth::user()->membership == 2) {
            if ($order->status != 0) {
                return redirect()->back();
            }
        }
        $order->status = 2;
        $order->save();

        $activity = DriverActivity::create([
            "user_id" => $order->user_id,
            "driver_id" => $order->driver_id,
            "survey_id" => $order->id,
            "option" => 1,
            "seen" => 0,
        ]);
        $activity = UserActivity::create([
            "user_id" => $order->user_id,
            "driver_id" => null,
            "survey_id" => $order->id,
            "option" => 1,
            "seen" => 1,
        ]);
        return redirect()->back();
    }

    public function succeed($id)
    {
        $order = Survey::find($id);

        if (Auth::user()->membership == 1) {
            if (!isset(Auth::user()->driver)) {
                return redirect()->back();
            } else {
                if ($order->driver_id != Auth::user()->driver->id) {
                    return redirect()->back();
                }
            }
        } elseif (Auth::user()->membership == 0) {
            if (Auth::id() != $order->user_id) {
                return redirect()->back();
            }
        }

        $order->status = 3;
        $order->save();

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

        return redirect()->back();
    }

    public function add_multiple()
    {
        $users = User::all();
        return view('quiz.add_multiple', compact('users'));
    }

    public function store_multiple(Request $request)
    {
        Excel::import(new SurveyImport(), $request->file('xlsx'));
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

        $activity = UserActivity::create([
            "user_id" => Auth::id(),
            "driver_id" => null,
            "survey_id" => $order->id,
            "option" => 0,
            "seen" => 0,
        ]);

        return redirect("quiz/show/$order->slug")->with('success', 'تم إرسال الطلب بنجاح');
    }
}
