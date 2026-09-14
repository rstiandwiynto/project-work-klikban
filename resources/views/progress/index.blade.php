@extends('layouts.app')

@section('title', 'Progress Overview — Workspace KlikBan')

@section('content')
<!-- ===================== VIEW: PROGRESS OVERVIEW ===================== -->
<section id="view-progress" class="view px-4 sm:px-6 py-6 flex-1 flex flex-col min-w-0 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-headline-lg text-[24px] sm:text-headline-lg text-textmain">Project Progress Overview</h1>
            <p class="text-body-md text-textsub mt-1">Pantau metrik utama dan aktivitas terbaru untuk sprint berjalan.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('board') }}" class="focus-ring inline-flex items-center gap-2 bg-surface border border-borderc hover:border-primary/50 transition-colors text-textmain text-body-md font-semibold px-4 py-2.5 rounded">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.7"/><rect x="14" y="3" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.7"/><rect x="3" y="14" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.7"/><rect x="14" y="14" width="7" height="7" rx="1.2" stroke="currentColor" stroke-width="1.7"/></svg>
                Kembali ke Board
            </a>
        </div>
    </div>

    <!-- 4 KPI Cards Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- 1. Total Tasks -->
        <div class="bg-surface border border-borderc rounded-card p-5">
            <div class="flex items-center justify-between">
                <span class="text-body-md text-textsub font-medium">Total Tasks</span>
                <span class="w-8 h-8 rounded bg-blue-50 flex items-center justify-center text-primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><rect x="4" y="4" width="16" height="16" rx="2" stroke="currentColor" stroke-width="1.7"/><path d="M8 9h8M8 13h8M8 17h4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                </span>
            </div>
            <p class="text-[28px] font-extrabold text-textmain mt-2">{{ $totalTasks }}</p>
            <p class="text-label-sm text-textsub font-medium mt-1">Semua kolom proyek</p>
        </div>

        <!-- 2. Completed -->
        <div class="bg-surface border border-borderc rounded-card p-5">
            <div class="flex items-center justify-between">
                <span class="text-body-md text-textsub font-medium">Completed</span>
                <span class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center text-success">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
            </div>
            <p class="text-[28px] font-extrabold text-textmain mt-2">{{ $doneTasks }}</p>
            <p class="text-label-sm text-success font-semibold mt-1">{{ $completionPercentage }}% selesai</p>
        </div>

        <!-- 3. In-Progress -->
        <div class="bg-surface border border-borderc rounded-card p-5">
            <div class="flex items-center justify-between">
                <span class="text-body-md text-textsub font-medium">In-Progress</span>
                <span class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.7"/><path d="M12 7v5l3.5 2" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
            </div>
            <p class="text-[28px] font-extrabold text-textmain mt-2">{{ $inProgressTasks + $reviewTasks }}</p>
            <p class="text-label-sm text-textsub font-medium mt-1">{{ $inProgressTasks }} In Progress, {{ $reviewTasks }} Review</p>
        </div>

        <!-- 4. Critical / Didahulukan -->
        <div class="bg-red-50/60 border border-red-100 rounded-card p-5">
            <div class="flex items-center justify-between">
                <span class="text-body-md text-accent font-semibold">Critical / Utama</span>
                <span class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-accent">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M12 9v4M12 17h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10.3 4.5L2.8 18a1.6 1.6 0 001.4 2.4h15.6a1.6 1.6 0 001.4-2.4L13.7 4.5a1.6 1.6 0 00-2.8 0z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
                </span>
            </div>
            <p class="text-[28px] font-extrabold text-accent mt-2">{{ $priorityStats['didahulukan']['count'] }}</p>
            <p class="text-label-sm text-accent font-bold uppercase tracking-wide mt-1">Perlu perhatian</p>
        </div>
    </div>

    <!-- Task Completion Widget (Large Circular Progress Ring) -->
    <div class="bg-surface border border-borderc rounded-card p-6">
        <h3 class="text-title-md text-[16px] text-textmain">Task Completion</h3>
        <div class="mt-6 flex flex-col items-center justify-center">
            <svg width="220" height="220" viewBox="0 0 220 220" class="progress-ring">
                <circle cx="110" cy="110" r="92" fill="none" stroke="#f1f1f8" stroke-width="18"/>
                <circle id="progressCircle" cx="110" cy="110" r="92" fill="none" stroke="#3B82F6" stroke-width="18" stroke-linecap="round"
                    stroke-dasharray="578" stroke-dashoffset="{{ 578 - (578 * ($completionPercentage / 100)) }}" transform="rotate(-90 110 110)"/>
                <text x="110" y="104" text-anchor="middle" font-family="Inter, sans-serif" font-size="34" font-weight="800" fill="#1b1b1f">{{ $completionPercentage }}%</text>
                <text x="110" y="128" text-anchor="middle" font-family="Inter, sans-serif" font-size="12" font-weight="700" letter-spacing="1.5" fill="#44474e">DONE</text>
            </svg>
            <div class="mt-5 flex items-center gap-6">
                <span class="flex items-center gap-2 text-body-md text-textmain">
                    <span class="w-3 h-3 rounded-[2px] bg-primary"></span> Completed ({{ $doneTasks }})
                </span>
                <span class="flex items-center gap-2 text-body-md text-textmain">
                    <span class="w-3 h-3 rounded-[2px] bg-borderc"></span> Remaining ({{ $totalTasks - $doneTasks }})
                </span>
            </div>
        </div>
    </div>

    <!-- 2 Column Section: Priority Breakdown & Team Workload -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        <!-- Breakdown Kategori Prioritas -->
        <div class="bg-surface rounded-card p-5 border border-borderc space-y-3">
            <div class="flex items-center justify-between pb-2.5 border-b border-borderc">
                <h3 class="text-title-md text-[15px] text-textmain flex items-center gap-2">
                    Breakdown Warna Prioritas
                </h3>
                <span class="text-label-sm text-textsub font-semibold">4 Kategori</span>
            </div>

            <div class="space-y-2.5">
                <!-- 1. Critical / Didahulukan -->
                <div class="p-3.5 rounded-card border border-red-200 bg-red-50/40 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-3 h-3 rounded-[2px] bg-accent"></span>
                        <div>
                            <p class="text-[12px] font-bold text-accent">Critical / Didahulukan</p>
                            <p class="text-[11px] text-textsub">Prioritas utama tim</p>
                        </div>
                    </div>
                    <span class="text-body-md font-bold text-accent px-2.5 py-0.5 bg-surface rounded-[2px] border border-red-200">
                        {{ $priorityStats['didahulukan']['count'] }} Tugas
                    </span>
                </div>

                <!-- 2. High / Perlu Diperhatikan -->
                <div class="p-3.5 rounded-card border border-amber-200 bg-amber-50/40 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-3 h-3 rounded-[2px] bg-amber-600"></span>
                        <div>
                            <p class="text-[12px] font-bold text-amber-700">High / Perlu Diperhatikan</p>
                            <p class="text-[11px] text-textsub">Tugas yang perlu pengawasan</p>
                        </div>
                    </div>
                    <span class="text-body-md font-bold text-amber-700 px-2.5 py-0.5 bg-surface rounded-[2px] border border-amber-200">
                        {{ $priorityStats['perlu_diperhatikan']['count'] }} Tugas
                    </span>
                </div>

                <!-- 3. Medium / Eksternal -->
                <div class="p-3.5 rounded-card border border-blue-200 bg-blue-50/40 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-3 h-3 rounded-[2px] bg-primary"></span>
                        <div>
                            <p class="text-[12px] font-bold text-primary">Medium / Eksternal</p>
                            <p class="text-[11px] text-textsub">Tugas tambahan / eksternal</p>
                        </div>
                    </div>
                    <span class="text-body-md font-bold text-primary px-2.5 py-0.5 bg-surface rounded-[2px] border border-blue-200">
                        {{ $priorityStats['eksternal']['count'] }} Tugas
                    </span>
                </div>

                <!-- 4. Low / Biasa -->
                <div class="p-3.5 rounded-card border border-emerald-200 bg-emerald-50/40 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-3 h-3 rounded-[2px] bg-success"></span>
                        <div>
                            <p class="text-[12px] font-bold text-success">Low / Biasa</p>
                            <p class="text-[11px] text-textsub">Tugas reguler harian</p>
                        </div>
                    </div>
                    <span class="text-body-md font-bold text-success px-2.5 py-0.5 bg-surface rounded-[2px] border border-emerald-200">
                        {{ $priorityStats['biasa']['count'] }} Tugas
                    </span>
                </div>
            </div>
        </div>

        <!-- Beban Kerja Tim -->
        <div class="bg-surface rounded-card p-5 border border-borderc space-y-3">
            <div class="flex items-center justify-between pb-2.5 border-b border-borderc">
                <h3 class="text-title-md text-[15px] text-textmain flex items-center gap-2">
                    Beban Kerja Tim
                </h3>
                <span class="text-label-sm text-textsub font-semibold">{{ $assigneeStats->count() }} Anggota</span>
            </div>

            <div class="space-y-2.5 max-h-[300px] overflow-y-auto pr-1">
                @forelse($assigneeStats as $stat)
                    <div class="p-3.5 rounded-card border border-borderc hover:bg-bg transition-colors space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded bg-primary text-white flex items-center justify-center font-bold text-[11px]">
                                    {{ strtoupper(substr($stat['name'], 0, 2)) }}
                                </div>
                                <span class="text-body-md font-bold text-textmain">{{ $stat['name'] }}</span>
                            </div>
                            <span class="text-label-sm font-bold text-textmain">{{ $stat['done'] }}/{{ $stat['total'] }} Selesai ({{ $stat['percent'] }}%)</span>
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full h-2 bg-bg rounded-[2px] overflow-hidden">
                            <div style="width: {{ $stat['percent'] }}%" class="h-full bg-success rounded-[2px] transition-all"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-body-md text-textsub py-6 text-center">Belum ada data penugasan.</p>
                @endforelse
            </div>
        </div>

    </div>
</section>
@endsection
