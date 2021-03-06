<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DeviceData;
use Illuminate\Support\Facades\DB;

class DeviceDataController extends Controller
{
    //
    static $model = \App\DeviceData::class;

    static $permissions = [
      'index' => ['app_r','sys_r'],
      ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($device_id, Request $req)
    {
        $res = DeviceData::where('created_at','>',$req->input('start_at'))->where('created_at','<',$req->input('end_at'))->where('device_id','=',$device_id)->limit(10)->get();
        //$res = $this->_index(['device_id', '=', $device_id], function (&$items) use ($req) {
        //    $items->orderBy('created_at', 'asc');
        //    if ($req->input('start_at')) {
        //        $items->where('created_at', '>=', $req->input('start_at'));
        //    }
        //    if ($req->input('end_at')) {
        //        $items->where('created_at', '<=', $req->input('end_at'));
        //    }
       // });
        // foreach ($res['items'] as $item) {
        //     if (!isset($item->config)) continue;
        //     $config = $item->config->data;
        //     $data = $item->data;
        //     foreach ($data as $key => $value) {
        //         $data[$key] += (@$config[$key]?:[]);
        //     }
        //     $item->data = $data;
        // }
        // foreach ($res['items'] as $item) {
        // }
        return $res;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($device_id, $id)
    {
        return $this->_show($id);
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
