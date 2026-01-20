<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\ResponseHelper;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $categories =  Category::all();
        // $categories =  Category::withAvg('books' , 'price')->get();
        $categories =  Category::withCount('books')->get();
       return ResponseHelper::success(' جميع الأصناف',$categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50|unique:categories',
            'image' => 'nullable|image|max:2048',
        ]);
        $category = new Category();
        $category->name = $request->name;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = uniqid('cat_') . '.' . $file->extension();
            $file->storeAs('category-images', $filename, 'public');
            $category->image = $filename;
        }
        $category->save();
        return ResponseHelper::success("تمت إضافة الصنف" , $category);
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => "required|max:50|unique:categories,name,$id",
            'image' => 'nullable|image|max:2048',
        ]);

        $category = Category::find($id);
        $category->name = $request->name;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = uniqid('cat_') . '.' . $file->extension();
            $file->storeAs('category-images', $filename, 'public');
            $category->image = $filename;
        }
        $category->save();
        return ResponseHelper::success("تم تعديل الصنف" , $category);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        $category = Category::find($id);

        if (!$category) {
            return ResponseHelper::error("الصنف غير موجود", 404);
        }

        if ($category->books()->count() > 0) {
            return ResponseHelper::error("لا يمكن حذف الصنف لأنه مرتبط بكتب.", 400);
        }

        $category->delete();
        return ResponseHelper::success("تم حذف الصنف" , $category);
    }
}
