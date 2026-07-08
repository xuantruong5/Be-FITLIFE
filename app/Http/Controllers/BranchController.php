<?php

namespace App\Http\Controllers;

use App\Http\Requests\Branch\StoreBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::withCount('trainers')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Lấy danh sách chi nhánh thành công.',
            'data'    => $branches,
        ]);
    }

    public function store(StoreBranchRequest $request)
    {
        $branch = Branch::create($request->only('name', 'address', 'phone'));

        return response()->json([
            'status'  => true,
            'message' => 'Tạo chi nhánh thành công.',
            'data'    => $branch,
        ], 201);
    }

    public function show(Request $request)
    {
        $id     = $request->route('id');
        $branch = Branch::with('trainers')->find($id);

        if (!$branch) {
            return response()->json([
                'status'  => false,
                'message' => 'Chi nhánh không tồn tại.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Lấy thông tin chi nhánh thành công.',
            'data'    => $branch,
        ]);
    }

    public function update(UpdateBranchRequest $request)
    {
        $id     = $request->route('id');
        $branch = Branch::find($id);

        if (!$branch) {
            return response()->json([
                'status'  => false,
                'message' => 'Chi nhánh không tồn tại.',
            ], 404);
        }

        $branch->update($request->only('name', 'address', 'phone'));

        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật chi nhánh thành công.',
            'data'    => $branch,
        ]);
    }

    public function destroy(Request $request)
    {
        $id     = $request->route('id');
        $branch = Branch::find($id);

        if (!$branch) {
            return response()->json([
                'status'  => false,
                'message' => 'Chi nhánh không tồn tại.',
            ], 404);
        }

        $branch->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Xóa chi nhánh thành công.',
        ]);
    }
}
