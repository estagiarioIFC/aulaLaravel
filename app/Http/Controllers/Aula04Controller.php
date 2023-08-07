<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\VarDumper\VarDumper;

class Aula04Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $info = json_decode(file_get_contents('info.json'));
       
        return view('teste.index', ["info"=>$info]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teste.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(file_exists('info.json'))
            $info = json_decode(file_get_contents('info.json'));
                            
        else 
            $info = [];

            $idsList = array_column($info, 'id');
            if($idsList){
                $auto_increment_id = max($idsList) + 1;
            }else{
                $auto_increment_id = 0;
            }
            
            if($request->prioridade){
                $prioridade = $request->prioridade;
            }else{
                $prioridade = 0;
            }

            if($request->prazo){
                $prazo = $request->prazo;
            }else{
                $prazo = 0;
            }

            if($request->cor){
                $cor = $request->cor;
            }else{
                $cor = "#000000";
            }

            $agora = date('Y-m-d H:i');
                
            $req = ['id'=>$auto_increment_id, 'descricao'=>$request->descricao, 'prioridade'=>$prioridade, 'cor'=>$cor, 'prazo'=>$prazo, 'criacao'=>$agora];
        
            array_push($info, $req);
        
        encodar($info);
        return redirect()->route('aula4.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $info = json_decode(file_get_contents('info.json'));
        $especif = [];

        foreach($info as $item){
            if($item->id == $id){
                $especif = $item; break; }}

        return view('teste.show', ["especif"=>$especif]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $info = json_decode(file_get_contents('info.json'));
        $espe = [];

        foreach($info as $item){
            if($item->id == $id){
                $espe = $item; break; }}

        return view('teste.edit', ["item"=>$espe]);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $info = json_decode(file_get_contents('info.json'));
        
         for($i = 0; $i < count($info); $i++){
            if($info[$i]->id == $id){
                $agora = date('Y-m-d H:i');

                if($request->prioridade){
                    $prioridade = $request->prioridade;
                }else{
                    $prioridade = 0;
                }
    
                if($request->prazo){
                    $prazo = $request->prazo;
                }else{
                    $prazo = 0;
                }

                if($request->cor){
                    $cor = $request->cor;
                }else{
                    $cor = "#000000";
                }

                $info[$i] = ["id"=>$id, 'descricao'=>$request->descricao, 'prioridade'=>$prioridade, 'cor'=>$cor, 'prazo'=>$prazo, 'criacao'=>$agora]; break;
            }}

        encodar($info);

        return redirect()->route('aula4.index');
    }

    public function valor(Request $request)
    {
        dd($request->valor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $id)
    {
         $info = json_decode(file_get_contents('info.json'));
         $new_info = [];

         foreach($info as $key => $k)
             if($k->id != $id)
                 array_push($new_info, $k);

         encodar($new_info);
        return redirect()->route('aula4.index');
}
}

function encodar($dados){
    $json = json_encode($dados);

    $file = fopen('info.json', 'w');

    fwrite($file, $json);
    fclose($file);
}