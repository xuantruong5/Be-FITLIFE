<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * GET /api/packages
     */
    public function index(Request $request)
    {
        $packages = Package::where('status', Package::HOAT_DONG)->get();

        return response()->json([
            'status'  => true,
            'message' => 'Lấy danh sách gói tập thành công.',
            'data'    => $packages,
        ]);
    }

    public function indexAdmin(Request $request)
    {
        $packages = Package::all();

        return response()->json([
            'status'  => true,
            'message' => 'Lấy danh sách gói tập thành công.',
            'data'    => $packages,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'slug'          => 'required|string|unique:packages,slug',
            'price'         => 'required|integer|min:0',
            'duration_days' => 'required|integer|min:1',
            'description'   => 'nullable|string',
            'status'        => 'nullable|in:0,1',
            'is_popular'    => 'nullable|boolean',
        ]);

        $package = Package::create($request->only(
            'name', 'slug', 'price', 'duration_days', 'description', 'status', 'is_popular'
        ));

        return response()->json([
            'status'  => true,
            'message' => 'Tạo gói tập thành công.',
            'data'    => $package,
        ], 201);
    }

    public function show(Request $request)
    {
        $id      = $request->route('id');
        $package = Package::find($id);

        if (!$package) {
            return response()->json([
                'status'  => false,
                'message' => 'Gói tập không tồn tại.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Lấy thông tin gói tập thành công.',
            'data'    => $package,
        ]);
    }

    public function update(Request $request)
    {
        $id      = $request->route('id');
        $package = Package::find($id);

        if (!$package) {
            return response()->json([
                'status'  => false,
                'message' => 'Gói tập không tồn tại.',
            ], 404);
        }

        $request->validate([
            'name'          => 'sometimes|required|string|max:255',
            'slug'          => 'sometimes|required|string|unique:packages,slug,' . $id,
            'price'         => 'sometimes|required|integer|min:0',
            'duration_days' => 'sometimes|required|integer|min:1',
            'description'   => 'nullable|string',
            'status'        => 'nullable|in:0,1',
            'is_popular'    => 'nullable|boolean',
        ]);

        $package->update($request->only(
            'name', 'slug', 'price', 'duration_days', 'description', 'status', 'is_popular'
        ));

        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật gói tập thành công.',
            'data'    => $package,
        ]);
    }

    /**
     * DELETE /api/admin/packages/{id}
     */
    public function destroy(Request $request)
    {
        $id      = $request->route('id');
        $package = Package::find($id);

        if (!$package) {
            return response()->json([
                'status'  => false,
                'message' => 'Gói tập không tồn tại.',
            ], 404);
        }

        $package->update(['status' => Package::DUNG]);

        return response()->json([
            'status'  => true,
            'message' => 'Đã dừng gói tập thành công.',
        ]);
    }
}
