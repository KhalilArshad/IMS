<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = Unit::get();
        return view('admin.products.index',compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'unit_name' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('add-unit')->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }
        try {
            if(!empty($request->update_unit_id)){
                Unit::where('id',$request->update_unit_id)
                ->update(['name'=>$request->unit_name,'description'=>$request->description]);
            }else{
                $unit = new Unit();
               $unit->name   = $request->unit_name;
               $unit->description   = $request->description;
               $unit->save();
            }
            return redirect()->back()->with(['status' => 'success', 'message' => 'Unit stored successfully']);
        } catch (Exception $e) {
            return redirect('add-unit')->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addItem()
    {
        $units = Unit::get();
        $items = Item::with('unit')->get();
        return view('admin.item.list',compact('units','items'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveItem(Request $request)
    {
        $rules = array(
            'item_name' => 'required',
            'unit_id' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('addItems')->withInput()->with(['status' => 'danger', 'message' => $validator->errors()->first()]);
        }
        try {
            if(!empty($request->update_item_id)){
                Item::where('id',$request->update_item_id)
                ->update(['name'=>$request->item_name,'unit_id'=>$request->unit_id,'alert_quantity'=>$request->alert_quantity,'description'=>$request->description]);
            }else{
                $item = new Item();
               $item->name   = $request->item_name;
               $item->unit_id   = $request->unit_id;
               $item->alert_quantity   = $request->alert_quantity;
               $item->description   = $request->description;
               $item->save();
            }
            return redirect()->back()->with(['status' => 'success', 'message' => 'item stored successfully']);
        } catch (Exception $e) {
            return redirect('addItems')->withInput()->with(['status' => 'danger', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

  
}
