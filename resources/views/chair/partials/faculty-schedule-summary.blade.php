<div class="faculty-schedule-modal-content faculty-summary-content">
    <header class="faculty-schedule-preview-hero faculty-summary-hero">
        <button type="button" class="faculty-schedule-preview-back" data-close-faculty-schedule><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to Instructors &amp; Subjects</button>
        <span class="faculty-schedule-preview-kicker">Subject schedule summary · {{ $activeTerm->academic_year }} · {{ $activeTerm->semester }}</span>
        <h1>{{ $faculty->name }}</h1>
        <p>Weekly meeting pattern for this instructor’s assigned subjects.</p>
    </header>

    <div class="faculty-summary-metrics" aria-label="Weekly summary">
        <article><span>Assigned subjects</span><strong>{{ $summaryRows->count() }}</strong></article>
        <article><span>Scheduled meetings / week</span><strong>{{ $totalWeeklyMeetings }}</strong></article>
        <article><span>Not scheduled this term</span><strong>{{ $unscheduledCount }}</strong></article>
    </div>

    @if($summaryRows->isEmpty())
        <div class="faculty-summary-empty">No assigned subjects were found for this instructor.</div>
    @else
        <div class="faculty-summary-table-wrap">
            <table class="faculty-summary-table">
                <thead><tr><th>Subject</th><th>Program / year</th><th>Days and time</th><th>Meetings / week</th></tr></thead>
                <tbody>
                    @foreach($summaryRows as $row)
                        <tr>
                            <td><strong>{{ $row['code'] }}</strong><span>{{ $row['title'] }}</span></td>
                            <td><strong>{{ $row['program'] }}</strong><span>{{ $row['year_level'] ?: 'Year not set' }}</span></td>
                            <td>
                                @forelse($row['meetings'] as $meeting)
                                    <div class="faculty-summary-meeting">
                                        <strong>{{ implode(' · ', array_values($meeting['days'])) }}</strong>
                                        <span>{{ $meeting['time'] }}</span>
                                        @if($meeting['count'] > count($meeting['days']))<small>{{ $meeting['count'] }} scheduled occurrences</small>@endif
                                    </div>
                                @empty
                                    <span class="faculty-summary-unscheduled">Not scheduled in the active term</span>
                                @endforelse
                            </td>
                            <td><span class="faculty-summary-count {{ $row['weekly_meetings'] === 0 ? 'is-empty' : '' }}">{{ $row['weekly_meetings'] }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
