<?php

namespace App\Http\Controllers;

use App\Models\Map;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index(){
        $maps = Map::latest()->where('user_id', auth()->user()->id)->get();
        $maps->map(function ($data) {
            $data->created = $data->created_at->format('D d/m/Y H:i:s A');
            $data->file = config('app.url').'/storage/'.$data->file;
            return $data;
        });
        return $maps;
    }

    function randomString() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 15; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|max:255',
            'map_file' => 'required|max:60000'
        ]);

        if($request->map_file->getClientOriginalExtension() != 'map'){
            return response()->json([
                'message' => 'File must end with .map'. $request->map_file->getClientOriginalExtension()
            ], 422);
        }

        $maps = Map::where('user_id', auth()->user()->id)->get();
        if(count($maps) < 10){
            $map = Map::create([
                'name' => $request->name,
                'user_id' => auth()->user()->id,
                'file' => $request->map_file->storeAs('maps',  str_replace(' ', '-', strtolower($request->name)).'-'.$this->randomString().'.'.$request->map_file->getClientOriginalExtension(), 'public_disk')
            ]);
    
            $map->created = $map->created_at->format('D d/m/Y H:i:s A');
            $map->file = config('app.url').'/storage/'.$map->file;
    
            return response()->json([
                'message' => 'Map has been uploaded',
                'data' => $map
            ], 201);
        }else{
            return response()->json([
                'message' => 'You have exceed the number of file uploads!'
            ], 422);
        }

    }
}