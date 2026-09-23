@include('footerheader.header')

<div class="wrapper">

    <div class="main-layout">

        @include('faculty.sidebar')

        <main class="content">

            @if(session('success'))
                <div class="alert success-alert">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert error-alert">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif


            <style>

                /* =========================================================
                   MAIN CONTAINER
                ========================================================= */

                .vacant-container {
                    max-width: 1250px;
                    margin: 0 auto;
                    padding: 10px 0 40px;
                }

                .vacant-title {
                    font-size: 28px;
                    font-weight: 800;
                    color: #173b27;
                    margin-bottom: 5px;
                }

                .vacant-subtitle {
                    color: #6b7280;
                    margin-bottom: 25px;
                    font-size: 14px;
                }


                /* =========================================================
                   ALERTS
                ========================================================= */

                .alert {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    padding: 13px 16px;
                    margin-bottom: 20px;
                    border-radius: 10px;
                    font-size: 14px;
                    font-weight: 600;
                }

                .success-alert {
                    background: #e8f7ed;
                    color: #176b3a;
                    border: 1px solid #b9e2c7;
                }

                .error-alert {
                    background: #fdecec;
                    color: #b42318;
                    border: 1px solid #f3b5b0;
                }


                /* =========================================================
                   SEARCH BOX
                ========================================================= */

                .search-box {
                    background: #ffffff;
                    border: 1px solid #e1e7e3;
                    border-radius: 14px;
                    padding: 22px;
                    margin-bottom: 25px;
                    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
                }

                .search-row {
                    display: flex;
                    gap: 18px;
                    align-items: end;
                    flex-wrap: wrap;
                }

                .form-group {
                    display: flex;
                    flex-direction: column;
                    gap: 7px;
                }

                .form-group label {
                    font-size: 13px;
                    font-weight: 700;
                    color: #344054;
                }

                .form-group input,
                .form-group select {
                    width: 220px;
                    height: 44px;
                    padding: 0 13px;
                    border: 1px solid #d0d5d2;
                    border-radius: 9px;
                    background: #fff;
                    font-size: 14px;
                    outline: none;
                    box-sizing: border-box;
                }

                .form-group input:focus,
                .form-group select:focus {
                    border-color: #198754;
                    box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.10);
                }

                .time-search-wrap {
                    position: relative;
                    width: 320px;
                }

                .time-search-wrap input {
                    width: 100%;
                    padding-left: 40px;
                    padding-right: 42px;
                }

                .time-search-icon {
                    position: absolute;
                    left: 14px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #198754;
                    pointer-events: none;
                    z-index: 2;
                }

                .clear-time-button {
                    position: absolute;
                    right: 8px;
                    top: 50%;
                    transform: translateY(-50%);
                    width: 30px;
                    height: 30px;
                    border: none;
                    border-radius: 50%;
                    background: #eef3f0;
                    color: #66736c;
                    cursor: pointer;
                    z-index: 3;
                }

                .clear-time-button:hover {
                    background: #dfe8e2;
                    color: #198754;
                }

                .time-search-help {
                    margin-top: 6px;
                    color: #7a8580;
                    font-size: 11px;
                    line-height: 1.4;
                    max-width: 320px;
                }

                .searching-time {
                    font-size: 11px;
                    color: #198754;
                    margin-top: 5px;
                    display: none;
                }

                .search-button {
                    height: 44px;
                    padding: 0 25px;
                    background: #198754;
                    color: #ffffff;
                    border: none;
                    border-radius: 9px;
                    cursor: pointer;
                    font-weight: 700;
                    transition: .2s;
                }

                .search-button:hover {
                    background: #157347;
                    transform: translateY(-1px);
                }


                /* =========================================================
                   FILTER BAR
                ========================================================= */

                .filter-wrapper {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 15px;
                    flex-wrap: wrap;
                    background: #ffffff;
                    border: 1px solid #e1e7e3;
                    border-radius: 14px;
                    padding: 15px;
                    margin-bottom: 25px;
                    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.04);
                }

                .filter-title {
                    font-size: 14px;
                    font-weight: 800;
                    color: #344054;
                }

                .filter-buttons {
                    display: flex;
                    gap: 8px;
                    flex-wrap: wrap;
                }

                .filter-btn {
                    border: 1px solid #d9dfdc;
                    background: #ffffff;
                    color: #344054;
                    padding: 9px 14px;
                    border-radius: 8px;
                    cursor: pointer;
                    font-size: 13px;
                    font-weight: 700;
                    transition: .2s;
                }

                .filter-btn:hover {
                    border-color: #198754;
                    color: #198754;
                }

                .filter-btn.active {
                    background: #198754;
                    color: #ffffff;
                    border-color: #198754;
                }

                .filter-count {
                    margin-left: 4px;
                    opacity: .8;
                }


                /* =========================================================
                   DEPARTMENT
                ========================================================= */

                .department-section {
                    margin-bottom: 30px;
                }

                .department-title {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    font-size: 20px;
                    font-weight: 800;
                    color: #173b27;
                    margin-bottom: 12px;
                }

                .department-title i {
                    color: #198754;
                }


                /* =========================================================
                   BUILDING
                ========================================================= */

                .building {
                    background: #ffffff;
                    border: 1px solid #e1e7e3;
                    border-radius: 14px;
                    margin-bottom: 18px;
                    overflow: hidden;
                    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.04);
                }

                .building-title {
                    display: flex;
                    align-items: center;
                    gap: 9px;
                    background: #f5f8f6;
                    padding: 14px 17px;
                    font-weight: 800;
                    font-size: 16px;
                    color: #344054;
                    border-bottom: 1px solid #e5e9e6;
                }

                .building-title i {
                    color: #198754;
                }

                .rooms {
                    display: grid;
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                    gap: 15px;
                    padding: 17px;
                }


                /* =========================================================
                   ROOM CARD
                ========================================================= */

                .room-card {
                    border: 1px solid #e1e7e3;
                    border-radius: 12px;
                    padding: 17px;
                    background: #ffffff;
                    box-shadow: 0 2px 7px rgba(0, 0, 0, 0.04);
                    transition: .2s;
                    min-width: 0;
                }

                .room-card:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.07);
                    border-color: #b9d7c4;
                }

                .room-card.hidden {
                    display: none;
                }

                .room-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    gap: 10px;
                    margin-bottom: 8px;
                }

                .room-name {
                    font-size: 17px;
                    font-weight: 800;
                    color: #172b1e;
                }

                .department-name {
                    font-size: 12px;
                    color: #7a8580;
                    margin-bottom: 13px;
                }


                /* =========================================================
                   STATUS
                ========================================================= */

                .room-status {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 6px 9px;
                    border-radius: 20px;
                    font-size: 11px;
                    font-weight: 800;
                    white-space: nowrap;
                }

                .status-available {
                    background: #e8f7ed;
                    color: #176b3a;
                }

                .status-occupied {
                    background: #fdecec;
                    color: #b42318;
                }

                .status-reserved {
                    background: #fff3e5;
                    color: #b54708;
                }

                .status-pending {
                    background: #fff8d8;
                    color: #8a6500;
                }


                /* =========================================================
                   ROOM FEATURES
                ========================================================= */

                .matched-time-list {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 6px;
                    margin-top: 2px;
                }

                .matched-time {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    margin-top: 2px;
                    padding: 6px 9px;
                    border-radius: 7px;
                    background: #eef8f1;
                    color: #176b3a;
                    font-size: 11px;
                    font-weight: 700;
                }

                .room-features {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 8px;
                    margin-top: 13px;
                }

                .feature {
                    background: #f7f9f8;
                    border: 1px solid #e9eeeb;
                    border-radius: 7px;
                    padding: 8px;
                    min-width: 0;
                }

                .feature-label {
                    display: block;
                    font-size: 10px;
                    text-transform: uppercase;
                    letter-spacing: .03em;
                    color: #7a8580;
                    margin-bottom: 2px;
                }

                .feature-value {
                    display: block;
                    font-size: 12px;
                    font-weight: 700;
                    color: #344054;
                    overflow-wrap: anywhere;
                }


                /* =========================================================
                   PURPOSE / DESCRIPTION
                ========================================================= */

                .room-purpose {
                    margin-top: 10px;
                    font-size: 12px;
                    color: #66736c;
                }

                .room-description {
                    margin-top: 8px;
                    font-size: 12px;
                    color: #66736c;
                    line-height: 1.5;
                }


                /* =========================================================
                   RESERVE BUTTON
                ========================================================= */

                .reserve-form {
                    margin-top: 15px;
                }

                .reserve-button {
                    width: 100%;
                    height: 40px;
                    border: none;
                    border-radius: 8px;
                    background: #198754;
                    color: #ffffff;
                    font-size: 13px;
                    font-weight: 800;
                    cursor: pointer;
                    transition: .2s;
                }

                .reserve-button:hover {
                    background: #157347;
                }


                /* =========================================================
                   EMPTY RESULT
                ========================================================= */

                .no-results {
                    padding: 30px;
                    background: #ffffff;
                    border: 1px solid #e1e7e3;
                    border-radius: 12px;
                    color: #777;
                    text-align: center;
                }


                /* =========================================================
                   RESPONSIVE
                ========================================================= */

                @media (max-width: 1000px) {

                    .rooms {
                        grid-template-columns: repeat(2, minmax(0, 1fr));
                    }

                }

                @media (max-width: 700px) {

                    .vacant-container {
                        padding: 5px 0 30px;
                    }

                    .vacant-title {
                        font-size: 23px;
                    }

                    .search-row {
                        flex-direction: column;
                        align-items: stretch;
                    }

                    .form-group input,
                    .form-group select,
                    .search-button,
                    .time-search-wrap {
                        width: 100%;
                    }

                    .filter-wrapper {
                        align-items: flex-start;
                        flex-direction: column;
                    }

                    .filter-buttons {
                        width: 100%;
                    }

                    .filter-btn {
                        flex: 1;
                    }

                    .rooms {
                        grid-template-columns: 1fr;
                    }

                }

            </style>


            <div class="vacant-container">

                <div class="vacant-title">
                    View Rooms
                </div>

                <div class="vacant-subtitle">
                    Search a date and time to view all room schedules and availability.
                </div>


                {{-- =====================================================
                     SEARCH
                ====================================================== --}}

                <div class="search-box">

                    <form
                        action="{{ route('faculty.vacant') }}"
                        method="GET"
                    >

                        <div class="search-row">

                            <div class="form-group">

                                <label>Date</label>

                                <input
                                    type="date"
                                    name="date"
                                    value="{{ request('date') }}"
                                    required
                                >

                            </div>


                            <div class="form-group">

                                <label>Time</label>

                                <div class="time-search-wrap">
                                    <i class="fa-regular fa-clock time-search-icon"></i>

                                    <input
                                        type="text"
                                        id="timeSearch"
                                        name="time"
                                        value="{{ request('time') }}"
                                        placeholder="Type start time, e.g. 8, 8:0, 8:30"
                                        autocomplete="off"
                                        inputmode="decimal"
                                        required
                                    >

                                    <button
                                        type="button"
                                        id="clearTimeSearch"
                                        class="clear-time-button"
                                        aria-label="Clear time"
                                        title="Clear time"
                                        style="display:none;"
                                    >
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>

                            </div>


                            <button
                                type="submit"
                                class="search-button"
                            >
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Search
                            </button>

                        </div>

                    </form>

                </div>


                {{-- =====================================================
                     RESULTS
                ====================================================== --}}

                <div id="vacantResults">

                @include('faculty.partials.vacant-results')

                </div>

            </div>

        </main>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const timeSearch = document.getElementById('timeSearch');
    const clearTimeSearch = document.getElementById('clearTimeSearch');
    const dateInput = document.querySelector('input[name="date"]');
    const searchForm = timeSearch
        ? timeSearch.closest('form')
        : null;
    const resultsContainer =
        document.getElementById('vacantResults');

    let searchTimer = null;
    let activeRequest = null;

    function updateClearButton() {
        if (!timeSearch || !clearTimeSearch) {
            return;
        }

        clearTimeSearch.style.display =
            timeSearch.value.trim() !== '' ? 'block' : 'none';
    }

    function validTimeSearch(value) {

        value = value.trim();

        if (value === '') {
            return false;
        }

        return /^(?:[0-9]|1[0-9]|2[0-3])(?::[0-5]?[0-9]?)?$/.test(value);
    }

    function showLoading() {
        if (!resultsContainer) {
            return;
        }

        resultsContainer.innerHTML = `
            <div class="no-results">
                <i class="fa-solid fa-spinner fa-spin"></i>
                <div style="margin-top:8px;">
                    Loading room schedules...
                </div>
            </div>
        `;
    }

    function showInitialMessage() {
        if (!resultsContainer) {
            return;
        }

        resultsContainer.innerHTML = `
            <div class="no-results">
                <i class="fa-regular fa-calendar"></i>
                <div style="margin-top:8px;">
                    Select a date and type a start time to check room availability.
                </div>
            </div>
        `;
    }

    function runTimeSearch() {

        if (!timeSearch || !dateInput || !resultsContainer) {
            return;
        }

        const date = dateInput.value;
        const time = timeSearch.value.trim();

        if (!date || !time) {
            showInitialMessage();
            return;
        }

        if (!validTimeSearch(time)) {
            return;
        }

        if (activeRequest) {
            activeRequest.abort();
        }

        showLoading();

        const url = new URL(
            searchForm.action,
            window.location.origin
        );

        url.searchParams.set('date', date);
        url.searchParams.set('time', time);
        url.searchParams.set('partial', '1');

        activeRequest = new XMLHttpRequest();

        activeRequest.open('GET', url.toString(), true);
        activeRequest.setRequestHeader(
            'X-Requested-With',
            'XMLHttpRequest'
        );
        activeRequest.setRequestHeader(
            'Accept',
            'text/html'
        );

        activeRequest.onload = function () {

            if (activeRequest.status >= 200 &&
                activeRequest.status < 300) {

                resultsContainer.innerHTML =
                    activeRequest.responseText;

                /* Reconnect status filter buttons after AJAX replacement. */
                attachFilters();
            } else {
                resultsContainer.innerHTML = `
                    <div class="no-results">
                        Unable to load room schedules. Please try again.
                    </div>
                `;
            }
        };

        activeRequest.onerror = function () {
            resultsContainer.innerHTML = `
                <div class="no-results">
                    Unable to connect to the server. Please try again.
                </div>
            `;
        };

        activeRequest.send();
    }

    function attachFilters() {

        const filterButtons =
            document.querySelectorAll('#vacantResults .filter-btn');

        const roomCards =
            document.querySelectorAll('#vacantResults .room-card');

        const departmentSections =
            document.querySelectorAll(
                '#vacantResults [data-department-section]'
            );

        const buildingSections =
            document.querySelectorAll(
                '#vacantResults [data-building-section]'
            );

        const noFilterResults =
            document.getElementById('no-filter-results');

        filterButtons.forEach(function (button) {

            button.addEventListener('click', function () {

                const selectedFilter = this.dataset.filter;

                filterButtons.forEach(function (btn) {
                    btn.classList.remove('active');
                });

                this.classList.add('active');

                let visibleRooms = 0;

                roomCards.forEach(function (card) {

                    const status =
                        card.dataset.roomStatus;

                    if (
                        selectedFilter === 'all' ||
                        status === selectedFilter
                    ) {
                        card.classList.remove('hidden');
                        visibleRooms++;
                    } else {
                        card.classList.add('hidden');
                    }
                });

                buildingSections.forEach(function (building) {

                    const visibleCards =
                        building.querySelectorAll(
                            '.room-card:not(.hidden)'
                        );

                    building.style.display =
                        visibleCards.length === 0 ? 'none' : '';
                });

                departmentSections.forEach(function (department) {

                    const visibleCards =
                        department.querySelectorAll(
                            '.room-card:not(.hidden)'
                        );

                    department.style.display =
                        visibleCards.length === 0 ? 'none' : '';
                });

                if (noFilterResults) {
                    noFilterResults.style.display =
                        visibleRooms === 0 ? 'block' : 'none';
                }
            });
        });
    }

    if (timeSearch) {

        updateClearButton();

        timeSearch.addEventListener('input', function () {

            updateClearButton();
            clearTimeout(searchTimer);

            const value = this.value.trim();

            if (!value) {
                showInitialMessage();
                return;
            }

            if (!validTimeSearch(value)) {
                return;
            }

            /*
            | 250ms keeps the search responsive while preventing a request
            | for every individual keystroke.
            */
            searchTimer = setTimeout(function () {
                runTimeSearch();
            }, 250);
        });

        timeSearch.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                clearTimeout(searchTimer);
                runTimeSearch();
            }
        });
    }

    if (dateInput) {

        dateInput.addEventListener('change', function () {

            clearTimeout(searchTimer);

            if (
                timeSearch &&
                timeSearch.value.trim() &&
                validTimeSearch(timeSearch.value.trim())
            ) {
                runTimeSearch();
            } else {
                showInitialMessage();
            }
        });
    }

    if (clearTimeSearch && timeSearch) {

        clearTimeSearch.addEventListener('click', function () {

            timeSearch.value = '';
            updateClearButton();

            if (activeRequest) {
                activeRequest.abort();
            }

            clearTimeout(searchTimer);
            showInitialMessage();
            timeSearch.focus();
        });
    }

    attachFilters();

});

</script>


@include('footerheader.footer')
