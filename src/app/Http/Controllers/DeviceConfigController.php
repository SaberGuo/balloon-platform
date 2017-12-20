<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DeviceConfig;

class DeviceConfigController extends Controller
{
    static $model = \App\DeviceConfig::class;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($device_id)
    {
        return $this->_index(['device_id', '=', $device_id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $device_id)
    {
        $this->validate($request, [
            'data' => 'required',
            ]);
        $body = $request->all();
        $body['device_id'] = $device_id;
        return $this->_store($body);
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
    public function update(Request $request, $device_id, $id)
    {
        $this->validate($request, [
            ]);
        return $this->_update($id, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->_destroy($id);
    }
}
