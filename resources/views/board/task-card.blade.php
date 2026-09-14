@php
    $isDone = ($task->status === 'done');
@endphp

<article 
    class="group {{ $isDone ? 'bg-emerald-50/40' : 'bg-surface' }} border border-borderc {{ $task->priority_border_class }} rounded-card p-4 hover:shadow-sm transition-all cursor-pointer relative"
    onclick="openQuickStatusModal({{ $task->id }}, {{ json_encode($task->title) }}, {{ json_encode($task->description ?? '') }}, {{ json_encode($task->priority_label) }}, {{ json_encode($task->priority_badge_class) }}, {{ json_encode($task->assignee_name ?? '') }}, {{ json_encode($task->status) }})"
    id="task-card-{{ $task->id }}"
>
    <!-- Card Top: Priority Pill Badge & Quick Delete (hover) -->
    <div class="flex items-center justify-between gap-2">
        <span class="inline-block text-[10px] font-bold uppercase tracking-wide px-2 py-1 rounded {{ $task->priority_badge_class }}">
            {{ $task->priority_label }}
        </span>

        <button 
            type="button" 
            onclick="event.stopPropagation(); deleteCurrentTaskFromCard('{{ $task->id }}')" 
            class="p-1 text-textsub hover:text-accent hover:bg-red-50 rounded transition-colors" 
            title="Hapus Tugas"
            aria-label="Hapus tugas"
        >
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2M10 11v6M14 11v6"/>
            </svg>
        </button>
    </div>

    <!-- Card Title -->
    <h3 class="text-title-md text-[15px] {{ $isDone ? 'text-textsub line-through decoration-textsub/50' : 'text-textmain' }} mt-2.5 line-clamp-2 leading-snug">
        {{ $task->title }}
    </h3>

    <!-- Card Description -->
    @if($task->description)
        <p class="text-body-md text-[13px] {{ $isDone ? 'text-textsub/70' : 'text-textsub' }} mt-1.5 leading-relaxed line-clamp-2">
            {{ $task->description }}
        </p>
    @endif

    <!-- Card Footer: Due Date, Assignee, Move Button -->
    <div class="mt-4 flex items-end justify-between gap-2">
        <div class="min-w-0 flex-1">
            @if($task->formatted_due_date)
                <span class="flex items-center gap-1.5 text-label-sm text-textsub truncate">
                    @if($isDone)
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" class="shrink-0 text-success"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    @else
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" class="shrink-0"><rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M8 3v4M16 3v4M3 10h18" stroke="currentColor" stroke-width="1.6"/></svg>
                    @endif
                    <span>{{ $task->formatted_due_date }}</span>
                </span>
            @endif
            <p class="text-body-md text-[13px] font-bold text-textmain mt-1 truncate" title="{{ $task->assignee_name }}">
                {{ $task->assignee_name ?: 'Seluruh Tim' }}
            </p>
        </div>

        <button 
            type="button"
            onclick="event.stopPropagation(); openQuickStatusModal({{ $task->id }}, {{ json_encode($task->title) }}, {{ json_encode($task->description ?? '') }}, {{ json_encode($task->priority_label) }}, {{ json_encode($task->priority_badge_class) }}, {{ json_encode($task->assignee_name ?? '') }}, {{ json_encode($task->status) }})" 
            class="move-task-btn focus-ring w-8 h-8 shrink-0 flex items-center justify-center bg-blue-50 text-primary hover:bg-blue-100 rounded transition-colors"
            title="Pindahkan status tugas"
            aria-label="Pindahkan tugas"
        >
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                <path d="M4 8h13M17 8l-3-3M17 8l-3 3M20 16H7M7 16l3-3M7 16l3 3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>
</article>
