<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TodosExport;

class TodoController extends Controller {
    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string',
            'assignee' => 'nullable|string',
            'due_date' => 'required|date|after_or_equal:today',
            'time_tracked' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:pending,open,in_progress,completed',
            'priority' => 'required|in:low,medium,high'
        ]);

        $validated['status'] = $validated['status'] ?? 'pending';
        $validated['time_tracked'] = $validated['time_tracked'] ?? 0;

        $todo = Todo::create($validated);

        return response()->json($todo, 201);
    }

    public function export(Request $request) {
        $todos = Todo::query();

        if ($request->title) {
            $todos->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->assignee) {
            $todos->whereIn('assignee', explode(',', $request->assignee));
        }

        if ($request->start && $request->end) {
            $todos->whereBetween('due_date', [$request->start, $request->end]);
        }

        if ($request->min && $request->max) {
            $todos->whereBetween('time_tracked', [$request->min, $request->max]);
        }

        if ($request->status) {
            $todos->whereIn('status', explode(',', $request->status));
        }

        if ($request->priority) {
            $todos->whereIn('priority', explode(',', $request->priority));
        }

        $data = $todos->get();

        return Excel::download(new TodosExport($data), 'todos.xlsx');
    }
}
