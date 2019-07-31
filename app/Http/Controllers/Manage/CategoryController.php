<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Ticket;
use DB;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('manage.categories.index');
    }

    public function list()
    {
        $categories=Category::on();
        return datatables($categories)->toJson();
    }

    public function save(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|unique:categories',
        ]);

        Category::create([
            'name'=>$request['name'],
            'created_by'=>auth()->user()->name,
        ]);

        
        return response()->json(['message'=>'Category has been successfully saved!']);
    }
    public function info($id)
    {
        $category=Category::find($id);
        abort_if($category==null,404,'Category could not be found!');
        return json_encode($category);
    }

    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'name'=>'required|unique:categories,name,'.$id,
        ]);

        $category=Category::find($id);
        abort_if($category==null,404,'Category could not be found!');

        try {

            # Update the tickets
            Ticket::where('category',$category->name)->update([
                'category'=>$request['name'],
            ]);

            $category->name=$request['name'];
            $category->updated_by=auth()->user()->name;

            $category->save();

            DB::commit();
            return response()->json(['message'=>'Category has been successfully saved!']);

        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }
    }

    public function delete($id)
    { 
        $category=Category::find($id);
        abort_if($category==null,404,'Category could not be found!'); 

        $category->delete();

        return response()->json(['message'=>'Category has been successfully deleted!']);
    }
}
