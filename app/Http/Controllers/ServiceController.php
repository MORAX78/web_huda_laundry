<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeOfService;

class ServiceController extends Controller
{
    public function index()
    {
        $services = TypeOfService::all();
        return view('service.index', compact('services'));
    }

    public function create()
    {
        return view('service.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required',
            'price' => 'required|numeric',
            'description' => 'nullable',
        ]);

        TypeOfService::create([
            'service_name' => $request->service_name,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        return redirect()->route('services.index')->with('success', 'Service berhasil ditambahkan');
    }

    public function edit($id)
    {
        $service = TypeOfService::findOrFail($id);
        return view('service.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $service = TypeOfService::findOrFail($id);

        $request->validate([
            'service_name' => 'required',
            'price' => 'required|numeric',
            'description' => 'nullable',
        ]);

        $service->update([
            'service_name' => $request->service_name,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        return redirect()->route('services.index')->with('success', 'Service berhasil diupdate');
    }

    public function destroy($id)
    {
        $service = TypeOfService::findOrFail($id);
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service berhasil dihapus');
    }
}
