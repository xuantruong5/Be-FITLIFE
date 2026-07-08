<?php

namespace App\Http\Controllers;

use App\Http\Requests\Package\StorePackageRequest;
use App\Http\Requests\Package\UpdatePackageRequest;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
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

    public function store(StorePackageRequest $request)
    {
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

    public function update(UpdatePackageRequest $request)
    {
        $id      = $request->route('id');
        $package = Package::find($id);

        if (!$package) {
            return response()->json([
                'status'  => false,
                'message' => 'Gói tập không tồn tại.',
            ], 404);
        }

        $package->update($request->only(
            'name', 'slug', 'price', 'duration_days', 'description', 'status', 'is_popular'
        ));

        return response()->json([
            'status'  => true,
            'message' => 'Cập nhật gói tập thành công.',
            'data'    => $package,
        ]);
    }

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
