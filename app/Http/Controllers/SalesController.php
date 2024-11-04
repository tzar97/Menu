<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta; 

class SalesController extends Controller
{
        public function getSales()
        {
        $sales = Venta::with('product')->get();
        return response()->json($sales);
        }

}
