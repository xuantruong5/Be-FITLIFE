<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * GET /api/admin/branches
     */
    public function index(Request $request)
    {
        $branches = Branch::withCount('trainers')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Lấy danh sách chi nhánh thành công.',
            'data'    => $branches,
        ]);
    }

    /**
     * POST /api/admin/branches
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone'   => 'nullable|string|max:20',
        ]);

        $branch = Branch::create($request->only('name', 'address', 'phone'));

        return response()->json([
            'status'  => true,
            'message' => 'Tạo chi nhánh thành công.',
            'data'    => $branch,
        ], 201);
    }

    /**
     * GET /api/admin/branches/{id}
     */
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

    /**
     * PUT /api/admin/branches/{id}
     */
    public function update(Request $request)
    {
        $id     = $request->route('id');
        $branch = Branch::find($id);

        if (!$branch) {
            return response()->json([
                'status'  => false,
                'message' => 'Chi nhánh không tồn tại.',
            ], 404);
        }

        $request->validate([
            'name'    => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:500',
            'phone'   => 'nullable|string|max:20',
        ]);

        $branch->update($request->only('name', 'address', 'phone'));

        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật chi nhánh thành công.',
            'data'    => $branch,
        ]);
    }

    /**
     * DELETE /api/admin/branches/{id}
     */
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
