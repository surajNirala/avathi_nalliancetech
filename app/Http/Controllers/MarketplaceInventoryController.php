<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MarketplaceInventory;
use Illuminate\Support\Facades\Validator;

class MarketplaceInventoryController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $marketinventories = MarketplaceInventory::get();
        if(count($marketinventories) > 0){
            return response()->json([
                'message' => 'data list.',
                'status'  => 200,
                'data'    => $marketinventories
            ], 200);
        }
        return response()->json([
            'message' => 'data list.',
            'status'  => 200,
            'data'    => []
        ], 200);
        } catch (\Exception $th) {
            return response()->json([
                'message' => 'Internal server error.',
                'status'  => 500,
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id'  => 'required',
                'name'     => 'required',
                'model'    => 'required',
                'year'     => 'required',
                'price'    => 'required',
                'color'    => 'required',
                'speed'    => 'required',
            ]);
        
            if($validation->fails()){
                return response()->json($validation->errors(), 422);
            }
            $marketplaceInventory = MarketplaceInventory::create($request->all());
            if(!empty($marketplaceInventory)){
                return response()->json([
                    'message' => 'Record created successfully.',
                    'status'  => 201,
                ], 201);
            }
            return response()->json([
                'message' => 'Record not created successfully.',
                'status'  => 403,
            ], 403);
        } catch (\Exception $th) {
            return response()->json([
                'message' => 'Internal server error.',
                'status'  => 500,
                'error'   => $th->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'search'  => 'required',
            ]);
        
            if($validation->fails()){
                return response()->json($validation->errors(), 422);
            }
            $marketinventories = MarketplaceInventory::where('name', 'LIKE', "%{$request->search}%") 
                                                    ->orWhere('color', 'LIKE', "%{$request->search}%")
                                                    ->orWhere('model', 'LIKE', "%{$request->search}%")
                                                    ->orWhere('year', 'LIKE', "%{$request->search}%")
                                                    ->orWhere('price', 'LIKE', "%{$request->search}%")
                                                    ->orWhere('speed', 'LIKE', "%{$request->search}%")
                                                    ->get();
            if(count($marketinventories) > 0){
                return response()->json([
                    'message' => 'data list.',
                    'status'  => 200,
                    'data'    => $marketinventories
                ], 200);
            }
            return response()->json([
                'message' => 'data list.',
                'status'  => 200,
                'data'    => []
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                'message' => 'Internal server error.',
                'status'  => 500,
                'error'   => $th->getMessage()
            ], 500);
        }
    }
}
