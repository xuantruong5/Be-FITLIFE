<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class TrainerController extends Controller
{
     public function test()
    {
         return response()->json([
        'message' => 'Test API thành công'
    ]);
    }
}
