@extends('layouts.app')

@section('title', ($project->name ?? 'Proyek Akhir RPL') . ' — Workspace KlikBan')

@section('content')
<!-- ===================== VIEW: BOARD ===================== -->
<section id="view-board" class="view px-4 sm:px-6 py-6 flex-1 flex flex-col min-w-0">
    <div class="board-scroll flex lg:grid lg:grid-cols-4 gap-5 overflow-x-auto pb-4 -mx-1 px-1 items-start">

        <!-- 1. COLUMN: TO-DO -->
        <div class="board-col flex-1 flex flex-col gap-3 min-w-[280px] lg:min-w-0" data-status="To-Do">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h2 class="text-title-md text-[16px] text-textmain">To-Do</h2>
                    <span class="col-count text-label-sm text-textsub bg-bg border border-borderc w-5 h-5 flex items-center justify-center rounded-full">
                        {{ $todoTasks->count() }}
                    </span>
                </div>
                <button class="text-textsub hover:text-textmain px-1 text-[18px] leading-none" aria-label="Opsi kolom" title="Opsi kolom">⋯</button>
            </div>

            <div class="card-list space-y-3">
                @forelse($todoTasks as $task)
                    @include('board.task-card', ['task' => $task])
                @empty
                    <div class="py-8 text-center text-label-sm text-textsub border border-dashed border-borderc rounded-card bg-surface/50">
                        Tidak ada tugas To-Do
                    </div>
                @endforelse
            </div>

            <button onclick="openCreateTaskModal('todo')" class="add-task-trigger focus-ring flex items-center justify-center gap-2 text-body-md font-semibold text-textsub hover:text-primary border border-dashed border-borderc hover:border-primary rounded-card py-3.5 transition-colors bg-surface/40 hover:bg-surface">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.7"/><path d="M12 8v8M8 12h8" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                Tambah Tugas
            </button>
        </div>

        <!-- 2. COLUMN: IN PROGRESS -->
        <div class="board-col flex-1 flex flex-col gap-3 min-w-[280px] lg:min-w-0" data-status="In-Progress">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h2 class="text-title-md text-[16px] text-textmain">In Progress</h2>
                    <span class="col-count text-label-sm text-textsub bg-bg border border-borderc w-5 h-5 flex items-center justify-center rounded-full">
                        {{ $inProgressTasks->count() }}
                    </span>
                </div>
                <button class="text-textsub hover:text-textmain px-1 text-[18px] leading-none" aria-label="Opsi kolom" title="Opsi kolom">⋯</button>
            </div>

            <div class="card-list space-y-3">
                @forelse($inProgressTasks as $task)
                    @include('board.task-card', ['task' => $task])
                @empty
                    <div class="py-8 text-center text-label-sm text-textsub border border-dashed border-borderc rounded-card bg-surface/50">
                        Tidak ada tugas In Progress
                    </div>
                @endforelse
            </div>

            <button onclick="openCreateTaskModal('in_progress')" class="add-task-trigger focus-ring flex items-center justify-center gap-2 text-body-md font-semibold text-textsub hover:text-primary border border-dashed border-borderc hover:border-primary rounded-card py-3.5 transition-colors bg-surface/40 hover:bg-surface">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.7"/><path d="M12 8v8M8 12h8" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                Tambah Tugas
            </button>
        </div>

        <!-- 3. COLUMN: REVIEW -->
        <div class="board-col flex-1 flex flex-col gap-3 min-w-[280px] lg:min-w-0" data-status="Review">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h2 class="text-title-md text-[16px] text-textmain">Review</h2>
                    <span class="col-count text-label-sm text-textsub bg-bg border border-borderc w-5 h-5 flex items-center justify-center rounded-full">
                        {{ $reviewTasks->count() }}
                    </span>
                </div>
                <button class="text-textsub hover:text-textmain px-1 text-[18px] leading-none" aria-label="Opsi kolom" title="Opsi kolom">⋯</button>
            </div>

            <div class="card-list space-y-3">
                @forelse($reviewTasks as $task)
                    @include('board.task-card', ['task' => $task])
                @empty
                    <div class="py-8 text-center text-label-sm text-textsub border border-dashed border-borderc rounded-card bg-surface/50">
                        Tidak ada tugas Review
                    </div>
                @endforelse
            </div>

            <button onclick="openCreateTaskModal('review')" class="add-task-trigger focus-ring flex items-center justify-center gap-2 text-body-md font-semibold text-textsub hover:text-primary border border-dashed border-borderc hover:border-primary rounded-card py-3.5 transition-colors bg-surface/40 hover:bg-surface">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.7"/><path d="M12 8v8M8 12h8" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                Tambah Tugas
            </button>
        </div>

        <!-- 4. COLUMN: DONE -->
        <div class="board-col flex-1 flex flex-col gap-3 min-w-[280px] lg:min-w-0" data-status="Done">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h2 class="text-title-md text-[16px] text-textmain">Done</h2>
                    <span class="col-count text-label-sm text-textsub bg-bg border border-borderc w-5 h-5 flex items-center justify-center rounded-full">
                        {{ $doneTasks->count() }}
                    </span>
                </div>
                <button class="text-textsub hover:text-textmain px-1 text-[18px] leading-none" aria-label="Opsi kolom" title="Opsi kolom">⋯</button>
            </div>

            <div class="card-list space-y-3">
                @forelse($doneTasks as $task)
                    @include('board.task-card', ['task' => $task])
                @empty
                    <div class="py-8 text-center text-label-sm text-textsub border border-dashed border-borderc rounded-card bg-surface/50">
                        Belum ada tugas selesai
                    </div>
                @endforelse
            </div>

            <button onclick="openCreateTaskModal('done')" class="add-task-trigger focus-ring flex items-center justify-center gap-2 text-body-md font-semibold text-textsub hover:text-primary border border-dashed border-borderc hover:border-primary rounded-card py-3.5 transition-colors bg-surface/40 hover:bg-surface">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.7"/><path d="M12 8v8M8 12h8" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                Tambah Tugas
            </button>
        </div>

    </div>
</section>
@endsection
