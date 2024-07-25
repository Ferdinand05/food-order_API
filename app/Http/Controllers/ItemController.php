<?php

namespace App\Http\Controllers;

use App\Http\Resources\DetailItemResource;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::latest()->get();

        return ItemResource::collection($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'price' => 'required|integer',
            'image_file' => 'nullable|image|mimes:png,jpg,jpeg'
        ]);

        $image_file = $request->file('image_file');
        if ($image_file) {
            $imageExt = $image_file->getClientOriginalExtension();
            $imageName = Carbon::now()->timestamp . '_' .  Str::slug($request->name);
            $imageFullName = $imageName . '.' . $imageExt;
            $image_file->storeAs('image-item', $imageFullName);
        } else {
            $imageFullName = null;
        }

        $item = Item::create([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $imageFullName
        ]);


        return new DetailItemResource($item);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Item::find($id);

        return new DetailItemResource($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:100',
            'price' => 'required|integer',
            'image_file' => 'nullable|image|mimes:png,jpg,jpeg'
        ]);

        $item = Item::where('id', $id)->first();
        $image_file = $request->file('image_file');
        if ($image_file) {
            $imageExt = $image_file->getClientOriginalExtension();
            $imageName = Carbon::now()->timestamp . '_' .  Str::slug($request->name);
            $imageFullName = $imageName . '.' . $imageExt;

            $image_file->storeAs('image-item', $imageFullName);

            // * Delete Old Image
            Storage::delete('image-item/' . $item->image);
        } else {
            $imageFullName = $item->image;
        }

        $item->update([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $imageFullName
        ]);

        return new DetailItemResource($item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        // return $id;
        $item  =  Item::where('id', $id)->first();
        $item->delete();

        return new DetailItemResource($item);
    }
}
