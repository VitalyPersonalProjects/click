<?php

namespace App\Http\Controllers;

use App\Models\Click;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClickController extends Controller
{
    // 1. Приём кликов через webhook
    public function store(Request $request)
    {
        $data = $request->validate([
            'click_id'  => 'required|string|unique:clicks,click_id',
            'offer_id'  => 'required|integer',
            'source'    => 'required|string|max:100',
            'timestamp' => 'required|date',
            'signature' => 'required|string|max:64',
        ]);

        $click = Click::create([
            'click_id'   => $data['click_id'],
            'offer_id'   => $data['offer_id'],
            'source'     => $data['source'],
            'clicked_at' => $data['timestamp'],
            'signature'  => $data['signature'],
        ]);

        return response()->json(['status' => 'ok', 'id' => $click->id]);
    }

    // 2. Агрегированная статистика
    public function report(Request $request)
    {
        $data = $request->validate([
            'from'   => 'required|date',
            'to'     => 'required|date',
            'sort'   => 'nullable|string|in:clicks,offers,sources,date',
        ]);

        $query = Click::query()
            ->whereBetween('clicked_at', [$data['from'], $data['to']])
            ->selectRaw('DATE(clicked_at) as date, offer_id, source, COUNT(*) as clicks')
            ->groupBy('date', 'offer_id', 'source');

        if (!empty($data['sort'])) {
            $query->orderBy($data['sort']);
        }

        return response()->json($query->get());
    }

    // 3. Экспорт кликов в Finance
    public function forward(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
        ]);

        $clicks = Click::query()
            ->whereDate('clicked_at', $data['date'])
            ->get();

        $response = Http::post('http://finance-service.local/api/import', [
            'date'   => $data['date'],
            'clicks' => $clicks,
        ]);

        return response()->json([
            'status'   => 'forwarded',
            'response' => $response->json(),
        ]);
    }
}
