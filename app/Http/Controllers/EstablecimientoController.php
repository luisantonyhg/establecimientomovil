<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Establecimiento;
use Illuminate\Http\Request;

class EstablecimientoController extends Controller
{


    public function create()
    {
        // consultar categorias
        $categorias = Categoria::all();

        return view('establecimientos.create', compact('categorias'));
    }


    public function store(Request $request)
    {
        //
    }


    public function show(Establecimiento $establecimiento)
    {
        //
    }


    public function edit(Establecimiento $establecimiento)
    {
        return 'Desde Edit';
    }


    public function update(Request $request, Establecimiento $establecimiento)
    {
        //
    }



    public function destroy(Establecimiento $establecimiento)
    {
        //
    }
}
