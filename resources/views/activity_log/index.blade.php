@extends('layouts.app')

@section('title', 'Log Aktivitas Proyek — Workspace KlikBan')

@section('content')
<!-- ===================== VIEW: ACTIVITY LOG ===================== -->
<section id="view-activity" class="view px-4 sm:px-6 py-6 flex-1 flex flex-col min-w-0 max-w-5xl mx-auto w-full space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h1 class="text-headline-lg text-[24px] sm:text-headline-lg text-textmain">Log Aktivitas Proyek</h1>
            <p class="text-body-md text-textsub mt-1">Pantau semua perubahan dan interaksi dalam proyek Anda secara real-time.</p>
        </div>

        <!-- Filter & Search Form -->
        <form method="GET" action="{{ route('activity-log') }}" class="flex flex-wrap items-center gap-2.5">
            <!-- Search -->
            <div class="relative">
                <span class="absolute inset-y-0 left-3 flex items-center text-textsub pointer-events-none">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.7"/><path d="M21 21l-4.3-4.3" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                </span>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari aktivitas..." 
                    class="focus-ring bg-surface border border-borderc rounded text-body-md text-textmain placeholder:text-textsub/70 pl-9 pr-3 py-2 outline-none w-full sm:w-56 transition-colors focus:border-primary"
                >
            </div>

            <!-- Action Filter -->
            <div class="relative">
                <select 
                    name="filter" 
                    onchange="this.form.submit()" 
                    class="focus-ring appearance-none pl-3 pr-8 py-2 bg-surface border border-borderc rounded text-body-md font-medium text-textmain focus:border-primary cursor-pointer transition-colors"
                >
                    <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>Semua Filter</option>
                    <option value="new_task" {{ request('filter') == 'new_task' ? 'selected' : '' }}>New Task</option>
                    <option value="status_update" {{ request('filter') == 'status_update' ? 'selected' : '' }}>Status Update</option>
                    <option value="priority_change" {{ request('filter') == 'priority_change' ? 'selected' : '' }}>Priority Change</option>
                    <option value="member_joined" {{ request('filter') == 'member_joined' ? 'selected' : '' }}>Member Joined</option>
                </select>
                <span class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-textsub">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
            </div>

            <!-- Date Filter -->
            <div class="relative">
                <input 
                    type="date" 
                    name="date" 
                    value="{{ request('date') }}" 
                    onchange="this.form.submit()" 
                    class="focus-ring pl-3 pr-3 py-2 bg-surface border border-borderc rounded text-body-md font-medium text-textmain focus:border-primary cursor-pointer transition-colors"
                >
            </div>

            @if(request()->hasAny(['search', 'filter', 'date']))
                <a href="{{ route('activity-log') }}" class="focus-ring p-2 text-textsub hover:text-textmain bg-surface border border-borderc rounded transition-colors" title="Reset Filter">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M3 12a9 9 0 109-9 9.75 9.75 0 00-6.74 2.74L3 8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 3v5h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            @endif
        </form>
    </div>

    <!-- Activity Log List Card -->
    <div class="bg-surface border border-borderc rounded-card overflow-hidden">
        @forelse($groupedLogs as $dateGroup => $activities)
            <!-- Date Group Header Pill -->
            <div class="px-5 pt-4 pb-2">
                <span class="text-label-sm uppercase tracking-wide text-textsub font-bold bg-bg px-2.5 py-1 rounded">
                    {{ $dateGroup }}
                </span>
            </div>

            <!-- Activity Rows -->
            <div>
                @foreach($activities as $act)
                    <div class="activity-item flex items-start gap-3.5 px-5 py-4 border-t border-borderc hover:bg-bg/60 transition-colors">
                        <!-- User Avatar -->
                        <span class="w-9 h-9 rounded-full {{ $act->user ? $act->user->avatar_color : 'bg-primary text-white' }} text-[12px] font-bold flex items-center justify-center shrink-0">
                            {{ $act->user_initials }}
                        </span>

                        <div class="min-w-0 flex-1">
                            <p class="text-body-md text-textmain leading-normal">
                                {!! preg_replace('/"([^"]+)"/', '<strong class="font-bold text-textmain">"$1"</strong>', e($act->description)) !!}
                            </p>
                            <span class="flex items-center gap-1.5 text-label-sm text-textsub mt-1.5">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6"/><path d="M12 7v5l3.5 2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                                {{ $act->formatted_time }}
                            </span>
                        </div>

                        <!-- Right Action Tag Badge -->
                        <span class="shrink-0 text-[10px] font-bold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $act->badge_class }}">
                            {{ $act->badge_text }}
                        </span>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="py-14 text-center">
                <div class="w-10 h-10 rounded bg-bg text-textsub flex items-center justify-center mx-auto mb-2">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.7"/><path d="M12 7v5l3.5 2" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                </div>
                <h3 class="text-title-md text-[15px] text-textmain">Belum Ada Aktivitas</h3>
                <p class="text-body-md text-textsub mt-0.5">Aktivitas baru akan otomatis dicatat di sini.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($logs->hasPages())
        <div class="pt-2 flex justify-center">
            <div class="bg-surface border border-borderc rounded-card px-4 py-2 flex items-center gap-4 text-body-md font-semibold text-textmain">
                @if($logs->onFirstPage())
                    <span class="text-textsub/40 cursor-not-allowed">« Sebelumnya</span>
                @else
                    <a href="{{ $logs->previousPageUrl() }}" class="text-primary hover:underline">« Sebelumnya</a>
                @endif

                <span class="text-textsub text-label-sm font-normal">Halaman {{ $logs->currentPage() }} dari {{ $logs->lastPage() }}</span>

                @if($logs->hasMorePages())
                    <a href="{{ $logs->nextPageUrl() }}" class="text-primary hover:underline">Muat Lebih Banyak »</a>
                @else
                    <span class="text-textsub/40 cursor-not-allowed">Muat Lebih Banyak »</span>
                @endif
            </div>
        </div>
    @endif
</section>
@endsection
