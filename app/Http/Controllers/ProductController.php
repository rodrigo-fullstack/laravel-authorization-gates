<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function index(string $data = null){
        $products = session()->get('products');

        // if there is not products in the session, attribute them
        if(!$products){
            // dd("Produtos não está definido na sessão...");
            $products = Product::all()->toArray();
            session()->put('products', $products);
        
        }

        // if there was a last update in table
        if($data !== null){
            $result = '';
            parse_str($data, $result);
            $data = $result['data'];
            // receive the data
            
            // if there was success update the session products with last product and data is present
            if($data['success']){
                
                $product = $data['product'];
                $products[] = $product;
                session()->put('products', $products);
                session()->put('success', $data['message']);

                unset($products);
            }
        }


        // dd('Produtos definido na sessão...');
        return gettype($data) === 'null' ? 
        view('dashboard')->with('products', session()->get('products')) :
        view('dashboard')->with('products', session()->get('products'));
        ;

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
        // if(Gate::allows('user_admin')){
        //     return "You can create products.";
        // }

        if(Gate::denies('user_admin')){
            return redirect()->route('dashboard')
                    ->withErrors([
                'authorization_error' => "You can't create products"
            ]);
        }
        // -----------------------
        
        // 1. validate data
        $request->validate([
            'name' => ['required', 'string', 'between:3,100'],
            'value' => ['required', 'numeric', 'min: 10', 'max: 1000']
        ]);
        // create new product
        $product = new Product;
        $product->name = $request->input('name');
        $product->value = $request->input('value');

        // store in db
        $product->save();

        
        // return to the view of dashboard
        return redirect()->route('dashboard', http_build_query([
            'data' => [
                'success' => true,
                'message' => 'Product created successfully',
            'from' => From::Create,
                'product' => $product->toArray()
            ]
        ]));
    }

    public function __get($property){
        if(!property_exists($this, $property)){
            return null;
        }
        
        return $this->$property;
    }

}
