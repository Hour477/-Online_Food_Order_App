<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::all();
        return view('tables.index', compact('tables'));
    }

    public function create()
    {
        return view('tables.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => 'required|string|unique:tables,table_number',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,occupied,reserved',
        ]);
        Table::create($validated);
        return redirect()->route('tables.index')
            ->with('success', 'Table created successfully!');
    }

    public function show(string $id)
    {
        $table = Table::findOrFail($id);
        return view('tables.show', compact('table'));
    }

    public function edit(string $id)
    {
       $table = Table::findOrFail($id);
       return view('tables.edit', compact('table'));
    }

    public function update(Request $request, string $id)
    {
        $table = Table::findOrFail($id);

        $validated = $request->validate([
            'table_number' => 'string|unique:tables,table_number,' . $table->id,
            'capacity' => 'integer|min:1',
            'status' => 'required|in:available,occupied,reserved',
        ]);

        $table->update($validated);
        return redirect()->route('tables.index')
            ->with('success', 'Table updated successfully!');
    }

    public function destroy(string $id)
    {
        Table::destroy($id);
        return redirect()->route('tables.index')->with('success', 'Table deleted successfully!');
    }
}