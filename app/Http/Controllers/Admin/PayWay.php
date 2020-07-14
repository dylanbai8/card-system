<?php
namespace App\Http\Controllers\Admin; use App\Library\Response; use Illuminate\Http\Request; use App\Http\Controllers\Controller; class PayWay extends Controller { function get(Request $sp147552) { $sp95caec = (int) $sp147552->input('current_page', 1); $sp11fa7d = (int) $sp147552->input('per_page', 20); $spd10097 = \App\PayWay::orderBy('sort')->where('type', $sp147552->input('type')); $spa1f2d3 = $sp147552->input('search', false); $spa55e11 = $sp147552->input('val', false); if ($spa1f2d3 && $spa55e11) { if ($spa1f2d3 == 'simple') { return Response::success($spd10097->get(array('id', 'name'))); } elseif ($spa1f2d3 == 'id') { $spd10097->where('id', $spa55e11); } else { $spd10097->where($spa1f2d3, 'like', '%' . $spa55e11 . '%'); } } $sp23f506 = $sp147552->input('enabled'); if (strlen($sp23f506)) { $spd10097->whereIn('enabled', explode(',', $sp23f506)); } $sp8b8475 = $spd10097->paginate($sp11fa7d, array('*'), 'page', $sp95caec); return Response::success($sp8b8475); } function edit(Request $sp147552) { $this->validate($sp147552, array('id' => 'required|integer', 'type' => 'required|integer|between:1,2', 'name' => 'required|string', 'sort' => 'required|integer', 'channels' => 'required|string', 'enabled' => 'required|integer|between:0,3')); $speb3ceb = (int) $sp147552->post('id'); $sp0de38e = \App\PayWay::find($speb3ceb); if (!$sp0de38e) { if (\App\PayWay::where('name', $sp147552->post('name'))->exists()) { return Response::fail('名称已经存在'); } $sp0de38e = new \App\PayWay(); } else { if (\App\PayWay::where('name', $sp147552->post('name'))->where('id', '!=', $sp0de38e->id)->exists()) { return Response::fail('名称已经存在'); } } $sp0de38e->type = (int) $sp147552->post('type'); $sp0de38e->name = $sp147552->post('name'); $sp0de38e->sort = (int) $sp147552->post('sort'); $sp0de38e->img = $sp147552->post('img'); $sp0de38e->channels = @json_decode($sp147552->post('channels')) ?? array(); $sp0de38e->comment = $sp147552->post('comment'); $sp0de38e->enabled = (int) $sp147552->post('enabled'); $sp0de38e->saveOrFail(); return Response::success(); } function enable(Request $sp147552) { $this->validate($sp147552, array('ids' => 'required|string', 'enabled' => 'required|integer|between:0,3')); $sp548f2b = $sp147552->post('ids'); $sp23f506 = (int) $sp147552->post('enabled'); \App\PayWay::whereIn('id', explode(',', $sp548f2b))->update(array('enabled' => $sp23f506)); return Response::success(); } function sort(Request $sp147552) { $this->validate($sp147552, array('id' => 'required|integer')); $speb3ceb = (int) $sp147552->post('id'); $sp0de38e = \App\PayWay::findOrFail($speb3ceb); $sp0de38e->sort = (int) $sp147552->post('sort'); $sp0de38e->save(); return Response::success(); } function delete(Request $sp147552) { $this->validate($sp147552, array('ids' => 'required|string')); $sp548f2b = $sp147552->post('ids'); \App\PayWay::whereIn('id', explode(',', $sp548f2b))->delete(); return Response::success(); } }