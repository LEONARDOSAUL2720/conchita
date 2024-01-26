<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;



class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
        // $vs_productos = Producto::where('status', '=', 1)
        //     ->join('users', 'users.id', '=', 'productos.user_id')
        //     ->select('productos.*', 'users.name', 'users.id as id_user', 'users.email')
        //     ->get();
        // $productos = $this->cargarDT($vs_productos);
        // return view('producto.index')->with('productos', $productos);

        

        $vs_productos = Producto::where('status', '=', 1)
            ->join('users', 'users.id', '=', 'productos.user_id')
            ->select('users.name', 'users.email', 'productos.*')
            ->get();
        $productos = $this->cargarDT($vs_productos);
        return view('producto.index')->with('productos', $productos);

    }

    public function cargarDT($consulta)
    {
        $productos = [];


        foreach ($consulta as $key => $value) {


            $ruta = "eliminar" . $value['id'];
            $eliminar = route('delete-producto', $value['id']);
            $actualizar = route('productos.edit', $value['id']);


            $acciones = '
           <div class="btn-acciones">
               <div class="btn-circle">
                   <a href="' . $actualizar . '" role="button" class="btn btn-success" title="Actualizar">
                       <i class="far fa-edit"></i>
                   </a>
                    <a href="#' . $ruta . '" role="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#' . $ruta . '">
                       <i class="far fa-trash-alt"></i>
                   </a>


               </div>
           </div>


            <!-- Modal -->
       <div class="modal fade" id="' . $ruta . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
           <div class="modal-dialog">
               <div class="modal-content">
                   <div class="modal-header">
                       <h5 class="modal-title" id="exampleModalLabel">¿Seguro que deseas eliminar este video?</h5>
                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                   </div>
                   <div class="modal-body">
                       <p class="text-primary">
                   <small>
                       ' . $value['id'] . ', ' . $value['title'] . '                 </small>
                 </p>
                   </div>
                   <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="' . $eliminar . '" type="button" class="btn btn-danger">Eliminar</a>
                   </div>
               </div>
           </div>
       </div>


       ';


            $productos[$key] = array(
                $acciones,
                $value['id'],
                $value['title'],
                $value['description'],
                $value['image'],
                // $value['video_path'],
                $value['name'],
                $value['email']
            );
        }


        return $productos;
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('producto.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        //validación de campos requeridos
        $this->validate($request, [
            'title' => 'required|min:5',
            'description' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png,gif'
        ]);


        $producto = new Producto();
        $user = Auth::user();
        $producto->user_id = $user->id;
        $producto->title = $request->input('title');
        $producto->description = $request->input('description');


        //Subida de la miniatura
        $image = $request->file('image');
        if ($image) {
            $image_path = time() . $image->getClientOriginalName();
            Storage::disk('images')->put($image_path, File::get($image));


            $producto->image = $image_path;
        }



        $producto->status = 1;


        $producto->save();
        return redirect()->route('productos.index')->with(array(
            'message' => 'El producto se ha subido correctamente'
        ));





    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $producto = Producto::findOrFail($id);
        return view('producto.edit', array(
            'producto' => $producto
        ));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $this->validate($request, [
            'title' => 'required|min:5',
            'description' => 'required',
        ]);


        $user = Auth::user();
        $producto = Producto::findOrFail($id);
        $producto->user_id = $user->id;
        $producto->title = $request->input('title');
        $producto->description = $request->input('description');


        //Subida de la miniatura
        $image = $request->file('image');
        if ($image) {
            $image_path = time() . $image->getClientOriginalName();
            Storage::disk('images')->put($image_path, File::get($image));


            $producto->image = $image_path;
        }

        $producto->status = 1;


        $producto->save();
        return redirect()->route('productos.index')->with(array(
            'message' => 'El Producto se ha actualizado correctamente'
        ));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $producto = Producto::find($id);
        if ($producto) {
            $producto->status = 0;
            $producto->update();
            return redirect()->route('productos.index')->with([
                "message" => "El producto se ha eliminado correctamente"
            ]);
        } else {
            return redirect()->route('productos.index')->with([
                "message" => "El producto que trata de eliminar no existe"
            ]);
        }
    }

    public function delete_producto($producto_id)
    {
        $producto = Producto::find($producto_id);
        if ($producto) {
            $producto->status = 0;
            $producto->update();
            return redirect()->route('productos.index')->with(array(
                "message" => "El producto se ha eliminado correctamente"
            ));
        } else {
            return redirect()->route('productos.index')->with(array(
                "message" => "El video que trata de eliminar no existe"
            ));
        }
    }




}
