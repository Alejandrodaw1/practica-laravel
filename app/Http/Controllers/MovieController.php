<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

//Models
use App\Peliculas;
use App\Generos;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Se obtienen todas las peliculas
        $data['datosPeliculas'] = Peliculas::all();
        return view("movie/index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Se obtienen todos los generos.
        $data['datosGeneros'] = Generos::all();
        return view("movie/form", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Se valida el formulario
        $request->validate([
            'portada' => 'required',
            'nombre' => 'required',
            'duracion' => 'required|numeric',
            'anyo' => 'required|numeric',
            'generos' => 'required'
        ]);

        // Se crea la pelicula y se añaden todos los campos. 
        $movie = new Peliculas($request->all());
        $movie->save();

        // Se crean las tuplas de la tabla intermedia enlazando los generos de la pelicula
        $movie->generos()->attach($request->generos);
        return redirect()->route('movie.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Se obtiene la pelicula y los generos a los que pertence
        $data["datosPelicula"] = Peliculas::find($id);
        $data["datosGenero"] = Peliculas::find($id)->generos;
        return view('movie/show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Se obtiene la pelicula, los datos a los que pertenece y todos los generos que existen
        $data['datosPelicula'] = Peliculas::find($id);
        $data['generosPelicula'] = $data['datosPelicula']->generos;
        $data['datosGeneros'] = Generos::all();
        return view('movie/form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Se valida el formulario
        $request->validate([
            'portada' => 'required',
            'nombre' => 'required',
            'duracion' => 'required|numeric',
            'anyo' => 'required|numeric',
            'generos' => 'required'
        ]);

        // Se busca la pelicula a modificar
        $movie = Peliculas::find($id);
        $movie->fill($request->all());
        $movie->save();

        // Si la tabla intermedia que contiene los generos a los que pertenece la pelicula
        $movie->generos()->sync($request->generos);
        return redirect()->route('movie.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /* Se busca la pelicula y se eliminan todos los
        generos a los que pertenece. */
        $peli = Peliculas::find($id);
        $peli->generos()->detach();
        Peliculas::destroy($id);

        // Se devuelve 1 para que ajax sepa que a funcionado.
        echo "1";
    }
}
