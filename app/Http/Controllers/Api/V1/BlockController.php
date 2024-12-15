<?php

namespace App\Http\Controllers\Api\V1;

use App\Enum\EventEnum;
use App\Models\Block;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class BlockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blocks = Block::all();



        foreach ($blocks as $key => $value) {

            if($value->exit_criterion == 'Demostracion')
            {
                $response = Http::get('https://app.monaros.co/sistema/index.php/public_routes/get_clients_not_attend_demo');


                
                // Verificar si la respuesta fue exitosa
                if ($response->successful()) {
                    
                    $users = $response->json();
                   

                    foreach ($users['data'] as $user) {
                        if (!empty ($user['correo'])) {

                        }
                    }
                    
                    

                    //return response()->json($users);
                }

                
            
                // Manejar posibles errores
                return response()->json([
                    'error' => 'No se logró conectar a la API'
                ], $response->status());
            }
        }

        


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Block $block)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Block $block)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Block $block)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Block $block)
    {
        //
    }
}
