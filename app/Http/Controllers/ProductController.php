<?php

namespace App\Http\Controllers;

use App\Http\States\From;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function index(string $data = null){
        // dd(session()->get('products'));
        if(session()->exists('success')){
            session()->remove('success');

        }
        $products = session()->get('products');
        // dd($products);

        // if there is not products in the session, attribute them
        if(!$products){
            // dd("Produtos não está definido na sessão...");
            $products = Product::all()->toArray();
            session()->put('products', $products);
            // dd($products);
            return view('dashboard')->with('products', session()->get('products'));
        }

        // dd($data);

        // if there was a last update in table
        if($data !== null){
            // dd($data);
            $result = '';
            parse_str($data, $result);
            $data = $result;
            // dd($data['success']);
            // receive the data
            
            // if there was success and data is present
            if($data['success']){
                
                // update the session products with last product 
                function changeSessionByFrom($products, $data){
                    // dd($product['from']);
                    // dd($product['from']['name']);
                    switch($data['from']['name']){
                        case From::Create->name: 
                            $products[] = $data['product']; 
                            break;
                        case From::Update->name: 
                            function updateProductFromSession($products, $data){
                                for($i = 0; $i < count($products); $i++){
                                    if(array_key_exists($i, $products)){
                                        // dd($products[$i]['id']);
                                        // dd(array_key_exists($i, $products));
                                        // dd($i);
                                        // dd("{$products[$i]['id']} = {$product['id']}",
                                        
                                        // $products[$i]['id'] === $product['id']);
                                        if($products[$i]['id'] === (int) $data['id']){
                                            
                                            $products[$i] = $data['product'];
                                            // dd($products);
                                            return $products;
                                        }
                                    }
                                }
                            }
                            updateProductFromSession($products, $data);
                            break;
                        case From::Delete->name: 
                            // dd($products);
                            function removeProductFromSession($products, $data){
                                for($i = 0; $i < count($products); $i++){
                                    if(array_key_exists($i, $products)){
                                        // dd($products[$i]['id']);
                                        // dd(array_key_exists($i, $products));
                                        // dd($i);
                                        // dd("{$products[$i]['id']} = {$product['id']}",
                                        
                                        // $products[$i]['id'] === $product['id']);
                                        if($products[$i]['id'] === (int) $data['id']){
                                            // dd($products);
                                            unset($products[$i]);
                                            // dd($products);
                                            return $products;
                                        }
                                    }
                                }
                                return $products;
                            }

                            return removeProductFromSession($products, $data);
                        default: 
                            throw new Exception("From variable doesn't exist");

                    };

                    return $products;
                    // if its update
                    // for($i = Product::first('id')->id; $i < count($products); $i++){
                    //     // change the products of the session
                    //     if($i === $product['id'] - 1){
                    //         $products[$i] = $product;
                    //         return $products;
                    //     }
                    // }

                    // if its not insert the product in the session
                    ;
                    // return $products;
                };

                $products = changeSessionByFrom($products, $data);
                // dd($products);
                // dd($products);
                session()->put('products', $products);
                session()->put('success', $data['message']);
                // dd('Session Products: ', session()->get('products'));

                unset($products);
            }
        }
        
        // $successMessage = session()->get('success');
        // !isset($successMessage) ?: session()->remove('success');
        // dd('Session Products: ', session()->get('products'));

        return view('dashboard')->with('products', session()->get('products'));

    }

    public function create(){
        return view('product-create');
    }
    
    public function store(Request $request){
        // -----------------------
        // from Auth Facades
        // de Facade Auth 
        
        // if(Auth::user()->can('user_admin')){
        //     return "You can create products.";
        // }
        // -----------------------
        
        // -----------------------
        // from Gate Facades
        // De Facade Gates
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
        
        $request->validate([
            'name' => ['required', 'string', 'between:3,100'],
            'value' => ['required', 'numeric', 'min: 10', 'max: 1000']
        ]);
        $product = new Product;
        $product->name = $request->input('name');
        $product->value = $request->input('value');

        $product->save();

        
        return redirect()->route('dashboard', http_build_query([
            'success' => true,
            'message' => 'Product created successfully',
            'from' => From::Create,
            'product' => $product->toArray()
        ]));
    }

    public function __get($property){
        if(!property_exists($this, $property)){
            return null;
        }
        
        return $this->$property;
    }
    
    public function change(?string $data = null){
        // -----------------------
        // from Auth Facades
        
        // if(Auth::user()->can('user_admin')){
        //     return "You can create products.";
        // }
        // -----------------------
        
        // -----------------------
        // from Gate Facades
        
        
        if(!Gate::allows('user_admin')){
            return redirect()->route('dashboard')
                    ->withErrors([
                'authorizationError' => "You can't update products"
            ]); 
        }

        if($data === null){
            return redirect()->route('dashboard')
                    ->withErrors([
                        'dataError' => "Data not provided for update..."
                ]);
        }

        // if(Gate::denies('user_admin')){
        //     return redirect()->route('dashboard')
        //             ->withErrors([
        //         'authorization_error' => "You can't create products"
        //     ]);
        // }
        // -----------------------
        $result = '';
        parse_str($data, $result);

        $data = $result;
        return view('product-update')->with('product', $data);
    }
    public function update(Request $request){
        // -----------------------
        // from Auth Facades
        
        // if(Auth::user()->can('user_admin')){
        //     return "You can create products.";
        // }
        // -----------------------
        
        // -----------------------
        // from Gate Facades
        if(!Gate::allows('user_admin')){
            return redirect()->route('dashboard')
                    ->withErrors([
                'authorization_error' => "You can't update products"
            ]); 
        }

        // if(Gate::denies('user_admin')){
        //     return redirect()->route('dashboard')
        //             ->withErrors([
        //         'authorization_error' => "You can't create products"
        //     ]);
        // }
        // -----------------------
        
        // validate data
        $request->validate([
            'name' => ['required', 'string', 'between:3,100'],
            'value' => ['required', 'numeric', 'min: 10', 'max: 1000']
        ]); 
        // update product
        $product = Product::find($request->id);
        if(!$product){
            return redirect()->route('dashboard')
                ->withErrors([
                    'dataError' => 'Product not found...'
                ]);
        }
        $product->name = $request->input('name');
        $product->value = $request->input('value');

        // store in db
        $product->save();

        // return to the view of dashboard
        return redirect()->route('dashboard', http_build_query([
            'id' => $product->id,
            'success' => true,
            'message' => 'Product updated successfully',
            'from' => From::Update,
            'product' => $product->toArray()
        ]));
    }

    public function delete(Request $request){
        if(Auth::user()->cannot('user_admin')){
            return redirect()->route('dashboard')
                        ->withErrors([
                            'authorizationError' => "You can't delete products..."
                        ]);
        }
        // validate id
        $request->validate(['id' => ['required', 'numeric']]);
        
        // delete with id
        $product = Product::destroy($request->id);
        // dd($product);

        return redirect()->route('dashboard', http_build_query([
            'id' => $request->id,
            'success' => true,
            'message' => 'Product deleted succesfully.',
            'from' => From::Delete,
        ]));
        
    }


}
