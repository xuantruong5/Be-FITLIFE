<?php

namespace App\Http\Controllers;

use App\Models\TrainerSalary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainerSalaryController extends Controller
{
    /**
     * GET /api/admin/salaries
     * Admin xem tất cả bảng lương
     */
    public function index(Request $request)
    {
        $query = TrainerSalary::with('trainer');

        if ($request->month) {
            $query->where('month', $request->month);
        }
        if ($request->year) {
            $query->where('year', $request->year);
        }
        if ($request->id_trainer) {
            $query->where('id_trainer', $request->id_trainer);
        }

        $salaries = $query->orderBy('year', 'desc')->orderBy('month', 'desc')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Lấy danh sách lương thành công.',
            'data'    => $salaries,
        ]);
    }

    /**
     * GET /api/trainer/my-salary
     * HLV xem lịch sử lương của mình
     */
    public function mySalary(Request $request)
    {
        $trainer  = Auth::guard('sanctum')->user();
        $salaries = TrainerSalary::where('id_trainer', $trainer->id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Lấy lịch sử lương thành công.',
            'data'    => $salaries,
        ]);
    }

    /**
     * POST /api/admin/salaries
     * Admin tạo bảng lương tháng cho HLV
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_trainer'  => 'required|exists:trainers,id',
            'month'       => 'required|integer|min:1|max:12',
            'year'        => 'required|integer|min:2020',
            'base_salary' => 'required|numeric|min:0',
            'bonus'       => 'nullable|numeric|min:0',
            'deduction'   => 'nullable|numeric|min:0',
            'note'        => 'nullable|string',
        ]);

        $exists = TrainerSalary::where('id_trainer', $request->id_trainer)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->exists();

        if ($exists) {
            return response()->json([
                'status'  => false,
                'message' => 'Đã tồn tại bảng lương tháng ' . $request->month . '/' . $request->year . ' cho HLV này.',
            ], 400);
        }

        $bonus     = $request->bonus     ?? 0;
        $deduction = $request->deduction ?? 0;
        $total     = $request->base_salary + $bonus - $deduction;

        $salary = TrainerSalary::create([
            'id_trainer'    => $request->id_trainer,
            'month'         => $request->month,
            'year'          => $request->year,
            'base_salary'   => $request->base_salary,
            'bonus'         => $bonus,
            'deduction'     => $deduction,
            'total_salary'  => $total,
            'status'        => TrainerSalary::PENDING,
            'calculated_at' => now(),
            'note'          => $request->note,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Tạo bảng lương thành công.',
            'data'    => $salary->load('trainer'),
        ], 201);
    }

    /**
     * GET /api/admin/salaries/{id}
     * Xem chi tiết bảng lương
     */
    public function show(Request $request)
    {
        $id     = $request->route('id');
        $salary = TrainerSalary::with('trainer')->find($id);

        if (!$salary) {
            return response()->json(['status' => false, 'message' => 'Bảng lương không tồn tại.'], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Lấy thông tin lương thành công.',
            'data'    => $salary,
        ]);
    }

    /**
     * PUT /api/admin/salaries/{id}
     * Admin cập nhật bảng lương
     */
    public function update(Request $request)
    {
        $id     = $request->route('id');
        $salary = TrainerSalary::find($id);

        if (!$salary) {
            return response()->json(['status' => false, 'message' => 'Bảng lương không tồn tại.'], 404);
        }

        if ($salary->status == TrainerSalary::PAID) {
            return response()->json([
                'status'  => false,
                'message' => 'Bảng lương đã thanh toán, không thể chỉnh sửa.',
            ], 400);
        }

        $request->validate([
            'base_salary' => 'sometimes|required|numeric|min:0',
            'bonus'       => 'nullable|numeric|min:0',
            'deduction'   => 'nullable|numeric|min:0',
            'note'        => 'nullable|string',
        ]);

        $base      = $request->base_salary ?? $salary->base_salary;
        $bonus     = $request->has('bonus')     ? $request->bonus     : $salary->bonus;
        $deduction = $request->has('deduction') ? $request->deduction : $salary->deduction;
        $total     = $base + $bonus - $deduction;

        $salary->update([
            'base_salary'   => $base,
            'bonus'         => $bonus,
            'deduction'     => $deduction,
            'total_salary'  => $total,
            'note'          => $request->note ?? $salary->note,
            'calculated_at' => now(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật bảng lương thành công.',
            'data'    => $salary,
        ]);
    }

    /**
     * POST /api/admin/salaries/{id}/pay
     * Admin xác nhận đã thanh toán lương
     */
    public function pay(Request $request)
    {
        $id     = $request->route('id');
        $salary = TrainerSalary::find($id);

        if (!$salary) {
            return response()->json(['status' => false, 'message' => 'Bảng lương không tồn tại.'], 404);
        }

        if ($salary->status == TrainerSalary::PAID) {
            return response()->json(['status' => false, 'message' => 'Lương đã được thanh toán trước đó.'], 400);
        }

        $salary->update([
            'status'  => TrainerSalary::PAID,
            'paid_at' => now(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Đã xác nhận thanh toán lương.',
            'data'    => $salary,
        ]);
    }
}
