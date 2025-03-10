<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    private ?array $products = null;

    public function index(){
        if(!$this->products){
            $this->products = Product::all()->toArray();
        }

        return view('dashboard')->with('products', $this->products);
    }

    public function create(){
        return view('product-create');
    }
    
    public function store(Request $request){
        // -----------------------
        // from Auth Facades
        
        // if(Auth::user()->can('user_admin')){
        //     return "You can create products.";
        // }
        // -----------------------
        
        // -----------------------
        // from Gate Facades
        if(Gate::allows('user_admin')){
            return "You can create products.";
        }

        // if(Gate::denies('user_admin')){
        //     return "You can't create products.";
        // }
        // -----------------------
        
        return "You can't create products";
    }

    public function __get($property){
        if(!property_exists($this, $property)){
            return null;
        }
        
        return $this->$property;
    }

}
