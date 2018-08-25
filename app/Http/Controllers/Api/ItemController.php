<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ItemController extends Controller
{
    public function index()
    {
        return ItemResource::collection(Item::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'key' => 'required|string|max:25',
        ]);

        if ($validator->fails()) {
            return new JsonResponse($validator->errors(), '400');
        }

        $item = Item::create([
            'name' => $request->name,
            'key' => $request->key,
        ]);

        return new ItemResource($item);
    }

    public function show(Item $item)
    {
        return new ItemResource($item);
    }

    public function update(Request $request, Item $item)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'key' => 'required|string|max:25',
        ]);

        if ($validator->fails()) {
            return new JsonResponse($validator->errors(), '400');
        }

        $item->update($request->only(['name', 'key']));

        return new ItemResource($item);
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json(null, 204);
    }
}
