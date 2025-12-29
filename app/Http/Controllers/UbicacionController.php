<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class UbicacionController extends Controller
{
    public function index()
    {
        $ubicaciones = DB::table('ubicaciones')->whereNull('deleted_at')->orderByDesc('id')->paginate(10);
        $bodegas = DB::table('ubicaciones')
            ->whereNotNull('bodega')
            ->where('bodega','!=','')
            ->whereNull('deleted_at')
            ->distinct()
            ->pluck('bodega');
        return view('ubicaciones.ubicaciones', compact('ubicaciones','bodegas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bodega_select' => ['nullable','string','max:255'],
            'bodega_new' => ['nullable','string','max:255'],
            'ubicacion' => ['required','string'],
        ]);
        $bodega = $request->input('bodega_new') ?: $request->input('bodega_select');
        DB::table('ubicaciones')->insert([
            'bodega' => $bodega,
            'ubicacion' => $request->input('ubicacion'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return Redirect::back()->with('status','Se ha añadido la ubicación correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bodega_select' => ['nullable','string','max:255'],
            'bodega_new' => ['nullable','string','max:255'],
            'ubicacion' => ['required','string'],
        ]);
        $bodega = $request->input('bodega_new') ?: $request->input('bodega_select');
        DB::table('ubicaciones')->where('id',$id)->update([
            'bodega' => $bodega,
            'ubicacion' => $request->input('ubicacion'),
            'updated_at' => now(),
        ]);
        return Redirect::back()->with('status','Ubicación actualizada correctamente.');
    }

    public function destroy($id)
    {
        DB::table('ubicaciones')->where('id',$id)->update(['deleted_at' => now()]);
        return Redirect::back()->with('status','Ubicación eliminada');
    }
}
