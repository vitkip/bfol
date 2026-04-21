<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()   { return view('admin.' . $this->module() . '.index'); }
    public function create()  { return view('admin.' . $this->module() . '.form'); }
    public function store(Request $request) { return redirect()->back(); }
    public function show($id) { return redirect()->back(); }
    public function edit($id) { return view('admin.' . $this->module() . '.form'); }
    public function update(Request $request, $id) { return redirect()->back(); }
    public function destroy($id) { return redirect()->back(); }
    private function module(): string { return str($this::class)->afterLast('\\')->replace('Controller','')->lower()->snake('-'); }
}
