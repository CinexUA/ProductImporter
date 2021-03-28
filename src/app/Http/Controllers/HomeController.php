<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Import;
use App\Models\ImportLog;
use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = Product::with('category','manufacturer')->orderByDesc('id')->paginate();
        return view('home', compact('products'));
    }

    public function reset()
    {
        //It is removed so that for the Observer works
        Import::all()->each->delete();
        //reset auto increment
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ImportLog::truncate();
        Import::truncate();
        Product::truncate();
        Category::truncate();
        Manufacturer::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        toastr()->success('Data was reset!');
        return redirect()->route('home');
    }
}
