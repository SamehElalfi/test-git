<?php

namespace App\Imports;

use App\Models\Survey;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
//use Illuminate\Support\Facades\Session as Session;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\View\View;
class SurveyImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */


    public function model(array $row)
    {
        $users = User::where('email', $row[1])->orwhere('phone', $row[2])->get();
        if (count($users) >= 1){
            return redirect()->back()->with('exist', 'لقد قمت بإضافة بيانات مستخدم مسجل بالفعل');
        }
        $user = User::create([
            'name' => $row[0],
            'email' => $row[1],
            'phone' => $row[2],
            'password' => Hash::make($row[3]),
        ]);
        return new Survey([
            "user_id" => $user->id,
            "driver_id" => null,
            "reason" => $row[4] ?? "غير محدد",
            "needs" => $row[5] ?? "ليس بحاجة لمساعد",
            "date_tour" => $row[6] ?? "فارغ",
            "time_date_tour" => $row[7] ?? "فارغ",
            "start_point" => $row[8] ?? "فارغ",
            "end_point" => $row[9] ?? "فارغ",
            "address" => $row[10] ?? "فارغ",
            "notes" => $row[11] ?? "لا توجد",
            "distance_time" => 164,
            "distance_kilo" => 8,
            "status" => 0,
            "slug" => time(),
            ]);
    }
}
