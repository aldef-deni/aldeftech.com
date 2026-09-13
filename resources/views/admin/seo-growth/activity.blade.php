@php
    $latest = $activity['latest'];
    $terminal = $latest && in_array($latest->status, ['completed', 'partial', 'failed'], true);
    $tone = ['ACTIVE' => 'primary', 'INACTIVE' => 'secondary', 'RUNNING' => 'info', 'FAILED' => 'danger'][$activity['status']];
    $formatTime = fn ($time) => $time?->copy()->timezone('Asia/Jakarta')->format('d M Y H:i:s') ?? 'Not recorded';
@endphp
<section class="card border-primary mb-4" aria-labelledby="seo-activity-title">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h5 class="mb-0" id="seo-activity-title">AI SEO Activity</h5>
        <span class="badge bg-label-{{ $tone }}">{{ $activity['status'] }}</span>
    </div>
    <div class="card-body">
        <dl class="row mb-3">
            <dt class="col-sm-4">Engine Status</dt><dd class="col-sm-8">{{ $activity['enabled'] ? 'Active' : 'Inactive' }}</dd>
            <dt class="col-sm-4">Last Run</dt><dd class="col-sm-8">{{ $formatTime($latest?->created_at) }}</dd>
            <dt class="col-sm-4">Last Run Status</dt><dd class="col-sm-8">{{ $latest?->status ?? 'No recorded runs' }}</dd>
            <dt class="col-sm-4">Next Scheduled Run</dt><dd class="col-sm-8">{{ $activity['next'] ? $formatTime($activity['next']) : ($activity['enabled'] ? 'Schedule unavailable' : 'Disabled') }} · Asia/Jakarta</dd>
            <dt class="col-sm-4">Last Successful Run</dt><dd class="col-sm-8">{{ $formatTime($activity['last_success']?->updated_at) }}</dd>
            <dt class="col-sm-4">Last Error</dt><dd class="col-sm-8">
                @if($activity['last_error'])
                    {{ $activity['last_error'] }} ({{ $formatTime($activity['last_failure']->updated_at) }})
                @else No recorded errors @endif
            </dd>
        </dl>
        @if($activity['stale'])
            <div class="alert alert-warning">The latest run has no completion record after one hour. Its actual process state is unknown.</div>
        @endif
        <h6>Today · Asia/Jakarta</h6>
        @unless($activity['has_today'])<p class="text-body-secondary">No AI SEO activity yet today.</p>@endunless
        <div class="row g-3 mb-3">
            @foreach($activity['metrics'] as $label => $count)
                <div class="col-6 col-lg-4"><div class="border rounded p-3 h-100"><div class="fs-4">{{ $count }}</div><small>{{ $label }}</small></div></div>
            @endforeach
        </div>
        <p class="small text-body-secondary">Analysis and link recommendation counts reflect today's stored article reviews. Applied changes are counted only from saved activity records; existing links are not assumed to be engine changes. Zero means no recorded action, not a successful run.</p>
        <details class="mb-4">
            <summary>Scheduler configuration &amp; next planned work</summary>
            <p class="mt-3">Scheduler: {{ $activity['configured'] ? 'Configured' : 'Configuration unavailable' }} · {{ $activity['enabled'] ? 'Enabled' : 'Disabled' }} · Asia/Jakarta</p>
            <ul>
                @foreach($activity['schedules'] as $schedule)
                    <li>{{ ucfirst($schedule['name']) }}: <code>{{ $schedule['expression'] }}</code>
                        @if($activity['enabled'] && $schedule['next']) — next {{ $formatTime($schedule['next']) }} @endif
                    </li>
                @endforeach
            </ul>
            <p>Daily: analyze articles, discover opportunities and prospects, prepare distribution drafts. Weekly: review refresh opportunities and prepare a summary. Work is subject to configured limits and previously processed periods.</p>
            <p class="small text-body-secondary">These are expected Laravel scheduler triggers. System cron execution cannot be confirmed from configuration alone; recorded runs show observed activity.</p>
        </details>
        <details class="mb-4" @if($latest && in_array($latest->status, ['failed', 'partial'], true)) open @endif>
            <summary>Last run details</summary>
            @if($latest)
                <dl class="row mt-3">
                    <dt class="col-sm-4">Run</dt><dd class="col-sm-8">{{ $latest->task }} #{{ $latest->id }} · {{ $latest->status }}</dd>
                    <dt class="col-sm-4">Started at</dt><dd class="col-sm-8">{{ $formatTime($latest->created_at) }}</dd>
                    <dt class="col-sm-4">Completed / ended at</dt><dd class="col-sm-8">{{ $terminal ? $formatTime($latest->updated_at) : 'Not recorded' }}</dd>
                    <dt class="col-sm-4">Duration</dt><dd class="col-sm-8">{{ $terminal ? (int) $latest->created_at->diffInSeconds($latest->updated_at) . ' seconds' : 'Unavailable until the run ends' }}</dd>
                </dl>
                <p class="small text-body-secondary">Start uses the run creation time; end uses the terminal status update time.</p>
                <ul>
                @foreach(['analysis', 'opportunities', 'backlinks', 'distribution', 'refresh', 'summary'] as $stage)
                    @if($item = data_get($latest->result, $stage))
                    <li>{{ ucfirst($stage) }}: {{ in_array(data_get($item, 'status'), ['completed', 'deferred'], true) ? $item['status'] : 'Unknown' }}
                        @if(in_array($stage, ['analysis', 'opportunities', 'backlinks'], true) && is_numeric(data_get($item, 'result')))
                            — {{ (int) $item['result'] }} items
                        @elseif($stage === 'distribution' && is_numeric(data_get($item, 'result')))
                            — <a href="{{ route('admin.seo-growth.index', ['pack_id' => (int) $item['result']]) }}#pack-{{ (int) $item['result'] }}">Distribution pack #{{ (int) $item['result'] }}</a>
                        @elseif($stage === 'refresh' && is_array(data_get($item, 'result')))
                            — {{ (int) data_get($item, 'result.analyzed', 0) }} analyzed, {{ (int) data_get($item, 'result.deferred', 0) }} deferred
                        @endif
                    </li>
                    @endif
                @endforeach
                </ul>
                @if(in_array($latest->status, ['failed', 'partial'], true))
                    <p class="text-danger">{{ app(\App\Services\SeoActivityService::class)->safeError($latest) }}</p>
                @endif
            @else<p class="mt-3">No SEO Growth run recorded yet.</p>@endif
        </details>
        <h6>Recent activity</h6>
        <ol class="list-unstyled mb-0">
        @forelse($activity['timeline'] as $entry)
            <li class="border-start ps-3 pb-3">
                <time class="small text-body-secondary" datetime="{{ $entry['time']->toIso8601String() }}">{{ $entry['time']->format('d M Y H:i') }} WIB</time>
                <div>{{ $entry['description'] }}@if($entry['count'] !== null) · {{ $entry['count'] }}@endif</div>
                @if($entry['url'])<a href="{{ $entry['url'] }}">{{ $entry['label'] }}</a>@endif
                <div class="small text-body-secondary">{{ $entry['action'] }}@if($entry['status']) · {{ $entry['status'] }}@endif</div>
            </li>
        @empty<li class="text-body-secondary">No recorded SEO activity.</li>@endforelse
        </ol>
    </div>
</section>
