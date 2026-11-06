# WEBROOMSCHED ERD Explanation

This ERD represents the current database structure and the main relationships used by WEBROOMSCHED. The boxes show primary keys (PK), foreign keys (FK), and selected fields that explain each entity's purpose. It focuses on the live scheduling workflow rather than every framework or historical table.

## Organization and academic catalog

`departments` stores each college's code, name, and branding. The interface calls these colleges, while the database retains the table and foreign-key name `departments`. A department can own multiple buildings, rooms, programs, and user accounts. Each building belongs to one department; rooms reference both their department and, where assigned, their building. Room records also describe capacity, equipment, purpose, and whether other departments may use the room.

`programs` identifies a college's programs using a name, short code, curriculum memorandum, major, and year level. Each program belongs to a department and can have multiple `subjects`. A subject records the course code and title, major, year level, and semester. This is the active catalog model used in subject management.

## User accounts and instructor assignments

`users` is the shared account table. Administrator, department chair, and instructor permissions are roles on user accounts; instructor records are not represented by a separate active `faculties` table in the current application. The `department_id` associates an account with its college. Students select a college, program, and year level to view published schedules; they do not need a separate student entity for that viewing flow.

`faculty_subjects` links an instructor account to a course assignment and stores the course/cohort details used by scheduling. Its `faculty_id` points to `users`, and its catalog `subject_id` points to `subjects` when linked. `faculty_subject_cohorts` is the bridge that lets one instructor assignment cover multiple catalog subjects or majors. `department_faculty_imports` records instructor imports into a department and the account that performed the import.

## Terms and schedules

`academic_terms` stores the academic year, semester, status, and active-term flag. The same term anchors published `schedules`, general `schedule_time_slots`, room-specific `room_schedule_time_slots`, and saved manual cell layouts in `schedule_cell_merges`. The two time-slot tables define the timetable rows globally and for a particular room, respectively. `schedule_cell_merges` stores the spreadsheet's manually merged cell layout for one room and term, so the layout can be restored when the published schedule is reopened.

Each `schedules` record represents a published class meeting. It references a room, academic term, instructor account, and faculty subject assignment, and carries the course, cohort, day, time, row position, and display/status details needed to render the schedule. A room, term, instructor, or assignment can therefore be associated with multiple schedule records.

## Reservations and schedule changes

`reservations` records a user's room reservation. It references a room and user, and can be linked to a schedule and academic term when the reservation is associated with scheduled activity. Its date, time, purpose, and status support approval and display in the relevant term.

`room_swap_requests` records a proposed temporary or permanent exchange between two users' rooms and, when applicable, their schedules. It keeps both room references, both user references, the requested time, type, reason, decision status, and any decline reason. The term is determined through the linked schedule records rather than a separate term field on this table.

`room_reassignments` records a schedule's move from an original room to a new room for a time period. `schedule_overrides` records a temporary room occupancy or schedule override; it can reference a schedule and instructor, but those links are nullable because an override may apply directly to a room and time without being attached to one schedule.

`schedule_merges` records a cross-college request to share a scheduled class. It connects the source schedule with the requesting college, catalog subject or instructor assignment, request group, and approval information. This supports shared classes across programs or majors while preserving the source schedule.

## Notifications and diagram scope

`notifications` is Laravel's polymorphic notification table. `notifiable_type` and `notifiable_id` identify the recipient model and record, usually a user account; they are not an ordinary single-column foreign key.

The diagram leaves out the old standalone `faculties` table and curriculum tables, which are not the active instructor/catalog model. It also leaves out schedule drafts because the system no longer uses that workflow, and the student schedule update feed because students now view the current schedule directly rather than receiving that separate update feed. Standard framework tables such as cache, sessions, and queued jobs are outside the scheduling domain covered here.
