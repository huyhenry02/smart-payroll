<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function showJournal(Request $request): View|Factory|Application
    {
        $month = $request->input('month', now()->format('Y-m'));
        $data = $this->getDataJournal($month);

        return view('page.accounting.journal', $data);
    }
    public function loadJournal(Request $request): JsonResponse
    {
        $month = $request->input('month', now()->format('Y-m'));
        $data = $this->getDataJournal($month);

        $html = view('page.accounting.journal-table', $data)->render();

        return response()->json([
            'html' => $html,
            'date_journaling' => $data['journals']->first()->date_journaling ?? now()->toDateString(),
            'description' => $data['journals']->first()->description ?? '',
        ]);
    }
    public function saveJournal(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $request->input('journals');
            $month = $request->input('month');
            [$year, $monthNum] = explode('-', $month);

            Journal::where('month', $monthNum)->where('year', $year)->delete();

            foreach ($data as $item) {
                Journal::create([
                    'month' => $monthNum,
                    'year' => $year,
                    'description' => $request->input('description', ''),
                    'date_journaling' => $item['date_journaling'] ?? now(),
                    'content' => $item['content'],
                    'debt_account' => $item['debt_account'],
                    'has_account' => $item['has_account'],
                    'amount' => $item['amount'],
                ]);
            }
            DB::commit();
            return response()->json(['status' => 'success']);
        }catch (Exception $exception) {
            DB::rollBack();
            return response()->json(['status' => 'error']);
        }
    }

    private function getDataJournal(string $month): array
    {
        [$year, $monthNum] = explode('-', $month);
        $journals = Journal::where('month', $monthNum)
            ->where('year', $year)
            ->orderBy('id')
            ->get();

        return [
            'month' => $month,
            'journals' => $journals,
        ];
    }
}
