                    <section class="chair-panel chair-requests-panel">
                        <header class="chair-panel-header">
                            <div>
                                <span class="chair-panel-kicker">REQUEST CENTER</span>
                                <h2>Instructor requests &amp; activity</h2>
                                <p>Review cross-College merge requests and follow room swap activity.</p>
                            </div>
                            <span class="chair-panel-icon"><i class="fa-solid fa-inbox" aria-hidden="true"></i></span>
                        </header>

                        <div class="chair-tabs" role="tablist" aria-label="Instructor request categories">
                            <button type="button" class="chair-tab active" id="chairMergeTab" role="tab" aria-selected="true" aria-controls="chairMergePanel" data-tab="chairMergePanel">
                                <i class="fa-solid fa-code-branch" aria-hidden="true"></i>
                                Merge requests
                                @if($pendingMergeCount > 0)<span class="chair-tab-count">{{ $pendingMergeCount }}</span>@endif
                            </button>
                            <button type="button" class="chair-tab" id="chairSwapsTab" role="tab" aria-selected="false" aria-controls="chairSwapsPanel" data-tab="chairSwapsPanel" tabindex="-1">
                                <i class="fa-solid fa-right-left" aria-hidden="true"></i> Room swaps
                                @if($swapRequestCount > 0)<span class="chair-tab-count muted">{{ $swapRequestCount }}</span>@endif
                            </button>
                        </div>

                        <div class="chair-tab-panel" id="chairMergePanel" role="tabpanel" aria-labelledby="chairMergeTab">
                            <div id="merge-requests" class="chair-panel-anchor">
                                <h3 class="chair-merge-section-title">Requests for your College</h3>
                            @if(collect($mergeRequests ?? [])->isNotEmpty())
                                <div class="chair-request-list">
                                    @foreach($mergeRequests as $merge)
                                        @php
                                            $mergeStatus = strtolower((string) ($merge->status ?? 'pending'));
                                            $requestedSubject = $merge->subject ?? $merge->facultySubject?->subjectCatalog;
                                            $requestedCode = $requestedSubject?->course_code ?? $merge->facultySubject?->course_code ?? 'Course request';
                                            $requestedTitle = $requestedSubject?->course_title ?? $merge->facultySubject?->subject ?? 'Course title unavailable';
                                            $existingProgram = $merge->schedule?->program ?? 'N/A';
                                            $requestedProgram = $requestedSubject?->program?->name ?? $merge->facultySubject?->program ?? 'N/A';
                                        @endphp
                                        <article class="chair-request-card">
                                            <div class="chair-request-heading">
                                                <span class="chair-request-icon"><i class="fa-solid fa-code-branch" aria-hidden="true"></i></span>
                                                <div class="chair-request-title">
                                                    <h3>{{ $requestedCode }} — {{ $requestedTitle }}</h3>
                                                    <p>Request to join {{ $merge->schedule?->course_code ?? 'scheduled class' }} — {{ $merge->schedule?->subject ?? 'Subject not specified' }}</p>
                                                </div>
                                                <span class="chair-status {{ $mergeStatus }}">{{ ucfirst($mergeStatus) }}</span>
                                            </div>

                                            <div class="chair-request-details">
                                                <div><span>Class program</span><strong>{{ $existingProgram }}</strong></div>
                                                <div><span>Requested program</span><strong>{{ $requestedProgram }}</strong></div>
                                                <div><span>Requesting College</span><strong>{{ $merge->department?->name ?? 'N/A' }}</strong></div>
                                                <div><span>Requested by</span><strong>{{ $merge->requester?->name ?? 'College chair' }}</strong></div>
                                                <div><span>Room</span><strong>{{ $merge->schedule?->room?->room_name ?? 'N/A' }}</strong></div>
                                                <div><span>Instructor</span><strong>{{ $merge->schedule?->instructor ?? 'N/A' }}</strong></div>
                                                <div><span>Time</span><strong>{{ $merge->schedule?->time ?? 'N/A' }}</strong></div>
                                            </div>

                                            @if($merge->decision_note)
                                                <p class="chair-merge-decision-note"><strong>Chair note:</strong> {{ $merge->decision_note }}</p>
                                            @endif

                                            @if($mergeStatus === 'pending')
                                                <div class="chair-request-actions">
                                                    <form action="{{ route('chair.merge.approve', $merge->id) }}" method="POST" onsubmit="return confirm('Approve this merge request?');">
                                                        @csrf
                                                        <button type="submit" class="chair-approve"><i class="fa-solid fa-check" aria-hidden="true"></i> Approve request</button>
                                                    </form>
                                                    <form action="{{ route('chair.merge.reject', $merge->id) }}" method="POST" onsubmit="return confirm('Decline this merge request?');">
                                                        @csrf
                                                        <input type="text" name="decision_note" maxlength="1000" class="chair-merge-note-input" placeholder="Optional reason for declining">
                                                        <button type="submit" class="chair-decline"><i class="fa-solid fa-xmark" aria-hidden="true"></i> Decline</button>
                                                    </form>
                                                </div>
                                            @elseif($mergeStatus === 'approved')
                                                <div class="chair-request-actions">
                                                    <span class="chair-approved-note"><i class="fa-solid fa-circle-check" aria-hidden="true"></i> Merge is active</span>
                                                    <form action="{{ route('chair.merge.remove', $merge->id) }}" method="POST" data-confirm-remove="Remove this merged program? The original class will remain unchanged." onsubmit="return confirm('Remove this merged program? The original class will remain unchanged.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="chair-decline"><i class="fa-solid fa-link-slash" aria-hidden="true"></i> Remove merge</button>
                                                    </form>
                                                </div>
                                            @endif
                                        </article>
                                    @endforeach
                                </div>
                            @else
                                <div class="chair-empty-state">
                                    <span><i class="fa-solid fa-inbox" aria-hidden="true"></i></span>
                                    <h3>No merge requests to review</h3>
                                    <p>New requests from other colleges will appear here.</p>
                                </div>
                            @endif
                            </div>

                        </div>

                        <div class="chair-tab-panel" id="chairSwapsPanel" role="tabpanel" aria-labelledby="chairSwapsTab" hidden>
                            @if(collect($swapRequests ?? [])->isNotEmpty())
                                <div class="chair-swap-list">
                                    @foreach($swapRequests as $swap)
                                        @php
                                            $swapStatus = strtolower((string) ($swap->status ?? 'pending'));
                                            $swapDate = $swap->swap_date?->format('M j, Y') ?? 'Date not specified';
                                            $swapTime = ($swap->start_time ? \Carbon\Carbon::parse($swap->start_time)->format('g:i A') : '—')
                                                . ' – ' . ($swap->end_time ? \Carbon\Carbon::parse($swap->end_time)->format('g:i A') : '—');
                                        @endphp
                                        <article class="chair-swap-row">
                                            <span class="chair-swap-icon"><i class="fa-solid fa-right-left" aria-hidden="true"></i></span>
                                            <div class="chair-swap-content">
                                                <div class="chair-swap-topline">
                                                    <strong>{{ $swap->requester?->name ?? 'Instructor' }}</strong>
                                                    <span class="chair-status {{ $swapStatus }}">{{ ucfirst($swapStatus) }}</span>
                                                </div>
                                                <p>{{ $swap->requesterRoom?->room_name ?? 'Room unavailable' }} <i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i> {{ $swap->targetRoom?->room_name ?? 'Room unavailable' }}</p>
                                                <div class="chair-swap-meta">
                                                    <span><i class="fa-regular fa-calendar" aria-hidden="true"></i> {{ $swapDate }}</span>
                                                    <span><i class="fa-regular fa-clock" aria-hidden="true"></i> {{ $swapTime }}</span>
                                                    <span><i class="fa-solid fa-user" aria-hidden="true"></i> With {{ $swap->targetUser?->name ?? 'Instructor' }}</span>
                                                </div>
                                                @if($swap->reason)
                                                    <div class="chair-swap-reason">{{ $swap->reason }}</div>
                                                @endif
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            @else
                                <div class="chair-empty-state">
                                    <span><i class="fa-solid fa-right-left" aria-hidden="true"></i></span>
                                    <h3>No room swap activity yet</h3>
                                    <p>Instructor room swap requests will be listed here.</p>
                                </div>
                            @endif
                        </div>
                    </section>