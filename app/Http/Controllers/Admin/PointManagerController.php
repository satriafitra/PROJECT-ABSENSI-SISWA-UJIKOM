<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PointRule;
use App\Models\FlexibilityItem;
use Illuminate\Http\Request;

class PointManagerController extends Controller
{
    public function index()
    {
        $rules = PointRule::all();
        $items = FlexibilityItem::all();
        return view('admin.gamification.index', compact('rules', 'items'));
    }

    public function storeRule(Request $request)
    {
        $request->validate([
            'rule_name' => 'required',
            'condition_operator' => 'required',
            'condition_value' => 'required',
            'point_modifier' => 'required|integer',
        ]);

        PointRule::create($request->all());
        return back()->with('success', 'Aturan poin berhasil ditambahkan');
    }

    public function storeItem(Request $request)
    {
        $request->validate([
            'item_name' => 'required',
            'point_cost' => 'required|integer',
        ]);

        FlexibilityItem::create($request->all());
        return back()->with('success', 'Item shop berhasil ditambahkan');
    }
}