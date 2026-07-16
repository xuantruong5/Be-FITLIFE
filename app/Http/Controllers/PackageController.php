<?php

namespace App\Http\Controllers;

// use App\Http\Requests\Package\StorePackageRequest;
use App\Http\Requests\Package\UpdatePackageRequest;
use App\Http\Requests\Admin\StorePackageRequest;
use App\Models\Package;
use Illuminate\Support\Str;
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

    public function storePackage(StorePackageRequest $request)
    {
        
        $durationDays = match ((int) $request->duration_months) {
            1 => 30,
            3 => 90,
            6 => 180,
            12 => 365,
            default => 30,
        };

        $package = Package::create([
            'name' => $request->name,
            'slug' => $request->slug ?? Str::slug($request->name),
            'price' => $request->price,
            'duration_days' => $durationDays,
            'description' => $request->description,
            'status' => $request->status,
            'is_popular' => $request->is_popular ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tạo gói tập thành công.',
            'data' => $package
        ], 201);
    }







}
