<div class="faculty-schedule-modal-content spreadsheet-page">
    <header class="faculty-schedule-preview-hero spreadsheet-hero">
        @if($scheduleModal ?? false)
            <button type="button" class="faculty-schedule-preview-back spreadsheet-back" data-close-faculty-schedule><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to Instructors &amp; Subjects</button>
        @else
            <a class="faculty-schedule-preview-back spreadsheet-back" href="{{ route('chair.setFaculty') }}"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to Instructors &amp; Subjects</a>
        @endif
        <span class="faculty-schedule-preview-kicker spreadsheet-kicker">Instructor schedule spreadsheet</span>
        <h1>{{ $faculty->name }}</h1>
        <p class="spreadsheet-subtitle">Read-only weekly schedule for {{ $faculty->department?->name ?? 'this instructor' }}.</p>
    </header>

    <div class="faculty-schedule-preview-meta spreadsheet-meta" aria-label="Schedule period">
        <span>{{ $activeTerm->academic_year }}</span>
        <span>{{ $activeTerm->semester }}</span>
        <span>MWF · TThS spreadsheet layout</span>
    </div>

    <section class="faculty-schedule-preview-sheet spreadsheet-shell" aria-label="{{ $faculty->name }} weekly timetable">
        <div class="faculty-schedule-preview-scroll spreadsheet-scroll">
            <table>
                <thead>
                    <tr><th scope="col">TIME</th><th scope="col">MON</th><th scope="col">WED</th><th scope="col">FRI</th><th scope="col">TIME</th><th scope="col">TUE</th><th scope="col">THU</th><th scope="col">TIME</th><th scope="col">SAT</th></tr>
                </thead>
                <tbody>
                    @foreach($scheduleRows as $row)
                        @if($row['position'] === 5)
                            <tr class="faculty-schedule-noon-break"><td colspan="9">NOON BREAK</td></tr>
                        @endif
                        <tr>
                            <th class="faculty-schedule-time" scope="row">{{ $row['label'] }}</th>
                            @foreach(['MON', 'WED', 'FRI'] as $dayKey)
                                <td>
                                    @forelse($row['days'][$dayKey] ?? [] as $schedule)
                                        <article class="faculty-schedule-class-card class-card" data-merge-signature="{{ $schedule->spreadsheet_merge_signature }}">
                                            <div class="faculty-schedule-class-heading"><strong>{{ $schedule->spreadsheet_course }}</strong>@if($schedule->spreadsheet_time)<span>{{ $schedule->spreadsheet_time }}</span>@endif</div>
                                            @if($schedule->spreadsheet_subject)<span>{{ $schedule->spreadsheet_subject }}</span>@endif
                                            <small>{{ $schedule->program ?: 'Program not set' }}{{ $schedule->year_level ? ' · ' . $schedule->year_level : '' }}{{ $schedule->major ? ' · ' . $schedule->major : '' }}</small>
                                            <small>{{ $schedule->room?->room_name ?: 'Room not set' }}{{ $schedule->room?->buildingRelation?->name ? ' · ' . $schedule->room->buildingRelation->name : '' }}</small>
                                        </article>
                                    @empty
                                        <span class="faculty-schedule-empty-cell">Empty</span>
                                    @endforelse
                                </td>
                            @endforeach
                            <th class="faculty-schedule-time" scope="row">{{ $row['label'] }}</th>
                            @foreach(['TUE', 'THU'] as $dayKey)
                                <td>
                                    @forelse($row['days'][$dayKey] ?? [] as $schedule)
                                        <article class="faculty-schedule-class-card class-card" data-merge-signature="{{ $schedule->spreadsheet_merge_signature }}">
                                            <div class="faculty-schedule-class-heading"><strong>{{ $schedule->spreadsheet_course }}</strong>@if($schedule->spreadsheet_time)<span>{{ $schedule->spreadsheet_time }}</span>@endif</div>
                                            @if($schedule->spreadsheet_subject)<span>{{ $schedule->spreadsheet_subject }}</span>@endif
                                            <small>{{ $schedule->program ?: 'Program not set' }}{{ $schedule->year_level ? ' · ' . $schedule->year_level : '' }}{{ $schedule->major ? ' · ' . $schedule->major : '' }}</small>
                                            <small>{{ $schedule->room?->room_name ?: 'Room not set' }}{{ $schedule->room?->buildingRelation?->name ? ' · ' . $schedule->room->buildingRelation->name : '' }}</small>
                                        </article>
                                    @empty
                                        <span class="faculty-schedule-empty-cell">Empty</span>
                                    @endforelse
                                </td>
                            @endforeach
                            <th class="faculty-schedule-time" scope="row">{{ $row['label'] }}</th>
                            <td>
                                @forelse($row['days']['SAT'] ?? [] as $schedule)
                                    <article class="faculty-schedule-class-card class-card" data-merge-signature="{{ $schedule->spreadsheet_merge_signature }}">
                                        <div class="faculty-schedule-class-heading"><strong>{{ $schedule->spreadsheet_course }}</strong>@if($schedule->spreadsheet_time)<span>{{ $schedule->spreadsheet_time }}</span>@endif</div>
                                        @if($schedule->spreadsheet_subject)<span>{{ $schedule->spreadsheet_subject }}</span>@endif
                                        <small>{{ $schedule->program ?: 'Program not set' }}{{ $schedule->year_level ? ' · ' . $schedule->year_level : '' }}{{ $schedule->major ? ' · ' . $schedule->major : '' }}</small>
                                        <small>{{ $schedule->room?->room_name ?: 'Room not set' }}{{ $schedule->room?->buildingRelation?->name ? ' · ' . $schedule->room->buildingRelation->name : '' }}</small>
                                    </article>
                                @empty
                                    <span class="faculty-schedule-empty-cell">Empty</span>
                                @endforelse
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
