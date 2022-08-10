<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cycle;
use App\Models\Group;

class CycleController extends Controller
{
  
    public function index()
    {
        //
    }
     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userRole = auth()->user()->role;
        if ($userRole === 'admin') {
            try{
                $cycle = new Cycle();
                if (Cycle::where('cycle', '=', $request->input('cycle'))->where('group_id', '=', $request->input('group_id'))->exists()) {
                    return response()->json(["status" => 302,"message" => "El ciclo ya existe"],200);
                }
                else {
                    $cycle->cycle = $request->cycle;
                    $cycle->start_date = $request->start_date;
                    $cycle->end_date = $request->end_date;
                    $cycle->status = $request->status;
                    $cycle->group_id = $request->group_id;
                    if($cycle->save()>=1){
                        return response()->json(['status'=>'ok','data'=>$cycle],201);
                    }
                }
            }catch(\Exception $e){
                return response()->json(["message" => $e->getMessage()],500);
            }
        }
        else {
            return response()->json(['message' => 'No tienes autorización para ejecutar esta acción, inicia sesión en una cuenta válida'],401);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show()
    {
        $userRole = auth()->user()->role;
        if ($userRole === 'admin') {
            try {
                $cycle = Cycle::join('groups', 'cycles.group_id', '=', 'groups.id')
                ->select('cycles.id', 'cycles.cycle', 'cycles.start_date', 'cycles.end_date', 'cycles.status', 'groups.group')
                ->orderBy('id', 'asc')
                ->get();
                return $cycle;
            }
            catch (\Exception $e) {
                return response()->json(["message" => $e->getMessage()],500);
            }
        }
        else {
            return response()->json(['message' => 'No tienes autorización para ejecutar esta acción, inicia sesión en una cuenta válida'],401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
