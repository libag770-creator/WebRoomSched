from pathlib import Path

path = Path('resources/views/faculty/my-bookings.blade.php')
content = path.read_text(encoding='utf-8')

content = content.replace('    use App\\Models\\RoomSwapRequest;\n', '')

start = content.index('| ROOM SWAP REQUESTS')
start = content.rfind('    /*', 0, start)
end = content.index('\n@endphp', start)
content = content[:start] + content[end:]

start = content.index('            <!-- TABS -->')
end = content.index('            <div\n                id="reservationStatusFilter"', start)
content = content[:start] + content[end:]

start = content.index('            <!-- ================================================= -->\n            <!-- SWAPPED ROOMS -->')
end = content.index('\n        </div>\n\n\n    </main>', start)
content = content[:start] + content[end:]

start = content.index('function showTab(tabId, button)\n{')
end = content.index('function filterReservations(', start)
content = content[:start] + content[end:]

start = content.index('    /*\n     * These are filters inside My Reservations.')
end = content.index('    const filterBar =', start)
content = content[:start] + content[end:]

start = content.index('function openSwapRequests()')
end = content.index('</script>', start)
content = content[:start] + content[end:]

path.write_text(content, encoding='utf-8')
print('Removed embedded room swap queries, tabs, panels, and scripts.')
