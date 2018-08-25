<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItem;
use App\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::orderBy('id', 'DESC')->get();
        return view('item.index', compact('items'));
    }

    public function show($id)
    {
        $item = Item::find($id);
        return view('item.show', compact('item'));
    }

    public function delete($id)
    {
        $item = Item::destroy($id);
        return redirect(route('index-item'));
    }

    public function create()
    {
        return view('item.create');
    }

    public function store(StoreItem $request)
    {
        $validated = $request->validated();

        $item = new Item();
        $item->name = $request->input('name');
        $item->key = $request->input('key');
        $item->save();

        return redirect(route('index-item'));
    }

    public function edit($id)
    {
        $item = Item::find($id);
        return view('item.edit', compact('item'));
    }

    public function update($id, StoreItem $request)
    {
        $item = Item::find($id);

        $validated = $request->validated();

        $item->update($request->only(['name', 'key']));

        return redirect(route('index-item'));
    }
}
