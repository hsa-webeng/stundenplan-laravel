import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {

    const alertInfoBox = document.getElementById('alert-cont');
    const alertInfoText = document.getElementById('alert-text');
    let alertTimeout;
    hideAlert();

    /**
     * Show an alert message.
     * @param {string} message
     * @param {string} type
     */
    function showAlert(message, type) {
        if (alertInfoText && alertInfoBox) {
            console.log(message, type);

            if (type !== 'error' && type !== 'success' && type !== 'info') {
                type = 'info';
            }

            clearTimeout(alertTimeout);

            alertInfoBox.classList.remove('alert-error', 'alert-success', 'alert-info', 'hidden');
            alertInfoBox.classList.add(`alert-${type}`);
            alertInfoText.textContent = message;

            // hide the alert after timeout
            alertTimeout = setTimeout(() => {
                hideAlert();
            }, 7500);
        }
    }

    function hideAlert() {
        if (alertInfoBox && alertInfoText) {
            alertInfoBox.classList.remove('alert-error', 'alert-success', 'alert-info');
            alertInfoBox.classList.add('hidden');
            alertInfoText.textContent = '';
        }
    }

    /*
     * -------- TIMETABLE DRAG AND DROP --------
     */

    // select the timetable, all subjects, and all timetable cells
    const timetable = document.querySelector('.timetable');

    if (timetable) {
        const subjects = document.querySelectorAll('.subject');
        const timetableCells = document.querySelectorAll('.timetable td');

        const COLOR_DEFAULT = 'color-1';
        const COLOR_ALTERNATE = 'color-2';
        const COLOR_CONFLICT_1 = 'color-3';
        const COLOR_CONFLICT_2 = 'color-4';

        let colorAssignments = {};

        // update timetable state on page load
        updateTimetableState();

        // reassign colors on page load
        reassignAllColors();

        // make each subject draggable
        subjects.forEach(subject => {
            // if the sidebar subject is not already in the timetable or subject is in the timetable but not a clone
            if (!checkIfSubjectIsInTimetable(subject.dataset.subjectId) || (subject.classList.contains('dropped-subject') && !subject.classList.contains('cloned'))) {
                makeDraggable(subject);
            }
        });

        // add drag and drop event listeners to each timetable cell
        timetableCells.forEach(cell => {
            cell.addEventListener('dragover', dragOver);
            cell.addEventListener('dragenter', dragEnter);
            cell.addEventListener('dragleave', dragLeave);
            cell.addEventListener('drop', dragDrop);
        });

        // add a click event listener to the timetable to handle the removal of subjects
        timetable.addEventListener('click', (event) => {
            const subjectElement = event.target.closest('.subject');
            if (subjectElement && !subjectElement.classList.contains('cloned')) {
                removeModule(subjectElement);
            }
        });

        // subject currently being dragged
        let draggedSubject = null;

        /**
         * Makes a subject draggable.
         * @param {HTMLElement} subject - The subject to make draggable.
         */
        function makeDraggable(subject) {
            subject.setAttribute('draggable', 'true');
            subject.addEventListener('dragstart', dragStart);
            subject.addEventListener('dragend', dragEnd);
        }

        function makeUnDraggable(subject) {
            subject.setAttribute('draggable', 'false');
            subject.removeEventListener('dragstart', dragStart);
            subject.removeEventListener('dragend', dragEnd);
        }

        /**
         * Handles the drag start event.
         * @param {DragEvent} event - The drag event.
         */
        function dragStart(event) {
            draggedSubject = this;
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', this.textContent);
            event.dataTransfer.setData('length', this.dataset.length);
            this.classList.add('dragging');
        }

        /**
         * Handles the drag end event.
         */
        function dragEnd() {
            this.classList.remove('dragging');
        }

        /**
         * Handles the drag over event.
         * @param {DragEvent} event - The drag event.
         */
        function dragOver(event) {
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';
        }

        /**
         * Handles the drag enter event.
         * @param {DragEvent} event - The drag event.
         */
        function dragEnter(event) {
            event.preventDefault();
            this.classList.add('hovered');
        }

        /**
         * Handles the drag leave event.
         */
        function dragLeave() {
            this.classList.remove('hovered');
        }

        /**
         * Handles the drop event when a subject is dropped onto a timetable cell.
         * @param {DragEvent} event - The drag event.
         */
        function dragDrop(event) {
            event.preventDefault();
            const subjectLength = parseFloat(event.dataTransfer.getData('length'));
            const {startTime, endTime, day} = getTimeAndDayFromCell(this, subjectLength);

            // check if the subject is already in the timetable
            const isSubjectInTimetable = checkIfSubjectIsInTimetable(draggedSubject.dataset.subjectId);

            if (isValidDropTarget(this, subjectLength)) {
                let newSubject = null;

                if (isSubjectInTimetable && draggedSubject.classList.contains('dropped-subject')) {
                    // if the subject is already in the timetable and we're moving it
                    newSubject = draggedSubject;
                    removeSubjectAndClones(draggedSubject.parentElement, parseFloat(draggedSubject.dataset.length), draggedSubject.dataset.subjectId);
                } else if (isSubjectInTimetable) {
                    // if the subject is in the timetable, but we're trying to add it again
                    this.classList.remove('hovered');
                    return;
                } else {
                    // if it's a new subject being added to the timetable
                    newSubject = document.createElement('div');
                    newSubject.className = draggedSubject.className;
                    newSubject.classList.add('dropped-subject');
                    newSubject.dataset.subjectId = draggedSubject.dataset.subjectId;
                    newSubject.dataset.length = subjectLength.toString();

                    // clone only p elements with class 'course_name' & 'course_stdg'
                    draggedSubject.querySelectorAll('p.course_name, p.course_stdg').forEach(p => {
                        newSubject.appendChild(p.cloneNode(true));
                    });
                }

                // update the subject's time and day information
                newSubject.dataset.startTime = startTime;
                newSubject.dataset.endTime = endTime;
                newSubject.dataset.day = day.toString();

                // assign or reassign color
                assignColorToSubject(newSubject, this);

                // update or create the time span
                let timeSpan = newSubject.querySelector('.time-span');
                if (!timeSpan) {
                    timeSpan = document.createElement('div');
                    timeSpan.classList.add('time-span');
                    newSubject.insertBefore(timeSpan, newSubject.firstChild);
                }
                timeSpan.textContent = `${startTime} - ${endTime}`;

                this.appendChild(newSubject);
                makeUnDraggable(draggedSubject);
                makeDraggable(newSubject);

                // create clones for multi-cell subjects
                let currentCell = this;
                for (let i = 1; i < subjectLength && currentCell; i++) {
                    currentCell = getNextCell(currentCell);
                    if (currentCell) {
                        const clone = newSubject.cloneNode(true);
                        makeUnDraggable(clone);
                        clone.classList.add('cloned');
                        currentCell.appendChild(clone);
                    } else {
                        console.warn('Reached end of timetable while creating clones');
                        break;
                    }
                }

                reassignAllColors();
                updateTimetableState();
            }

            this.classList.remove('hovered');
        }

        /**
         * Checks if a cell is a valid drop target for a subject of a given length.
         * @param {HTMLElement} cell - The cell to check.
         * @param {number} length - The length of the subject.
         * @returns {boolean} - Whether the cell is a valid drop target.
         */
        function isValidDropTarget(cell, length) {
            const cellIndex = Array.from(timetableCells).indexOf(cell);
            const colIndex = cellIndex % 7;

            if (colIndex === 0) {
                return false;
            }

            let remainingLength = length;
            let currentCell = cell;
            while (remainingLength > 0 && currentCell) {
                const currentCellIndex = Array.from(timetableCells).indexOf(currentCell);
                const currentColIndex = currentCellIndex % 7;

                if (currentColIndex === 0) {
                    currentCell = getNextCell(currentCell);
                    continue;
                }

                const existingSubject = currentCell.querySelector('.dropped-subject');
                if (existingSubject && parseFloat(existingSubject.dataset.length) > 1) {
                    remainingLength--;
                } else if (!currentCell.classList.contains('blocked')) {
                    remainingLength--;
                }
                currentCell = getNextCell(currentCell);
            }

            return remainingLength === 0;
        }

        /**
         * Returns the next cell in the timetable.
         * @param {Element} cell - The current cell.
         * @returns {HTMLElement|null} - The next cell in the timetable, or null if there is no next cell.
         */
        function getNextCell(cell) {
            const cellIndex = Array.from(timetableCells).indexOf(cell);
            const rowIndex = Math.floor(cellIndex / 7);
            const nextRowIndex = rowIndex + 1;

            if (nextRowIndex * 7 >= timetableCells.length) {
                return null;
            } else {
                return timetableCells[nextRowIndex * 7 + (cellIndex % 7)];
            }
        }

        /**
         * Returns the start time, end time, and day for a given cell and subject length.
         * @param {HTMLElement} cell - The cell.
         * @param {number} length - The length of the subject.
         * @returns {Object} - An object containing the start time, end time, and day.
         */
        function getTimeAndDayFromCell(cell, length) {
            const cellIndex = Array.from(timetableCells).indexOf(cell);
            const row = cell.parentElement;
            const startTimeText = row.querySelector('td:first-child').textContent.split(' - ')[0];
            const endTimeText = calculateEndTime(startTimeText, length);
            const day = cellIndex - 1;
            return {startTime: startTimeText, endTime: endTimeText, day};
        }

        /**
         * Calculates the end time for a subject given its start time and length.
         * @param {string} startTime - The start time of the subject.
         * @param {number} length - The length of the subject.
         * @returns {string} - The end time of the subject.
         */
        function calculateEndTime(startTime, length) {

            // TODO: this calculation is not correct for all cases
            //  (e.g. with length 3 and start time 11:45 -> should be 17.30, but is 16.45)

            const [startHour, startMinute] = startTime.split(':').map(Number);

            let additionalMinutes = 0;
            if (length === 2) {
                additionalMinutes = 15;
            } else if (length === 3) {
                additionalMinutes = 30;
            } else if (length > 3) {
                additionalMinutes = 45 * (length - 1);
            }

            let totalMinutes = startMinute + (length * 90) + additionalMinutes;
            if (length === 2 && startTime === '11:45') {
                totalMinutes += 45;
            }
            let endHour = startHour + Math.floor(totalMinutes / 60);
            let endMinute = totalMinutes % 60;

            if (endMinute < 10) {
                endMinute = '0' + endMinute;
            }

            return `${endHour}:${endMinute}`;
        }

        /**
         * Removes a subject element from its parent cell and unblocks the cells it occupied.
         * @param {HTMLElement} subjectElement - The subject element to remove.
         */
        function removeModule(subjectElement) {
            const subjectId = subjectElement.dataset.subjectId;
            const parentCell = subjectElement.parentElement;
            const subjectLength = parseFloat(subjectElement.dataset.length);

            removeSubjectAndClones(parentCell, subjectLength, subjectId);

            // remove color assignment when the subject is removed from the timetable
            delete colorAssignments[subjectId];

            // enable draggable for the original subject
            const originalSubject = document.querySelector(`.subject[data-subject-id="${subjectId}"]`);
            makeDraggable(originalSubject);

            // reassign colors for all courses
            reassignAllColors();
            updateTimetableState();
        }

        /**
         * Removes a subject and its clones from a cell and unblocks the cells they occupied.
         * @param {Element} cell - The cell to remove the subject and its clones from.
         * @param {number} length - The length of the subject.
         * @param subjectId
         */
        function removeSubjectAndClones(cell, length, subjectId) {
            let currentCell = cell;
            let removedSubjects = 0;

            while (removedSubjects < length && currentCell) {
                const subjects = currentCell.querySelectorAll('.dropped-subject, .cloned');
                subjects.forEach(subject => {
                    if (subject.dataset.subjectId === subjectId) {
                        currentCell.removeChild(subject);
                        removedSubjects++;
                    }
                });
                currentCell = getNextCell(currentCell);
            }
        }

        function checkIfSubjectIsInTimetable(subjectId) {
            const subjects = document.querySelectorAll('.dropped-subject');
            for (let i = 0; i < subjects.length; i++) {
                if (subjects[i].dataset.subjectId === subjectId) {
                    return true;
                }
            }
            return false;
        }


        /*
         * -------- TIMETABLE COLORS --------
         */

        /**
         * Assigns a color to a subject based on its position in the timetable.
         * @param subject
         * @param startCell
         */
        function assignColorToSubject(subject, startCell) {
            const subjectId = subject.dataset.subjectId;
            const subjectLength = parseInt(subject.dataset.length);
            let colorClass;

            // check for conflicts
            const conflictingCourses = getConflictingCourses(startCell, subjectLength);

            if (conflictingCourses.length > 1) {
                // determine the color based on the position in the conflicting group
                const conflictIndex = conflictingCourses.findIndex(course => course.dataset.subjectId === subjectId);
                colorClass = conflictIndex % 2 === 0 ? COLOR_CONFLICT_1 : COLOR_CONFLICT_2;
            } else {
                // alternate between default and alternate color
                const adjacentCourse = checkAdjacentCourses(startCell, subjectLength);
                colorClass = adjacentCourse && adjacentCourse.classList.contains(COLOR_DEFAULT) ? COLOR_ALTERNATE : COLOR_DEFAULT;
            }

            // assign the color class
            colorAssignments[subjectId] = colorClass;
            subject.classList.remove(COLOR_DEFAULT, COLOR_ALTERNATE, COLOR_CONFLICT_1, COLOR_CONFLICT_2);
            subject.classList.add(colorClass);

            // apply the same color to all clones
            applyColorToClones(subject, startCell, subjectLength);
        }

        /**
         * Check for adjacent courses above and below the course being added. Return the first adjacent course found.
         * @param startCell
         * @param length
         * @returns {Element|null}
         */
        function checkAdjacentCourses(startCell, length) {
            let currentCell = startCell;
            const column = Array.from(currentCell.parentNode.children).indexOf(currentCell);

            // check the cell above the first cell of the course
            const cellAbove = currentCell.parentNode.previousElementSibling?.children[column];
            if (cellAbove && cellAbove.querySelector('.dropped-subject')) {
                return cellAbove.querySelector('.dropped-subject');
            }

            // check the cell below the last cell of the course
            for (let i = 1; i < length; i++) {
                currentCell = getNextCell(currentCell);
            }
            const cellBelow = currentCell?.parentNode.nextElementSibling?.children[column];
            if (cellBelow && cellBelow.querySelector('.dropped-subject')) {
                return cellBelow.querySelector('.dropped-subject');
            }

            return null;
        }

        /**
         * Apply the color of the original subject to all its clones
         * @param subject
         * @param startCell
         * @param length
         */
        function applyColorToClones(subject, startCell, length) {
            const colorClass = colorAssignments[subject.dataset.subjectId];
            let currentCell = startCell;
            for (let i = 0; i < length; i++) {
                const clone = currentCell.querySelector(`.cloned[data-subject-id="${subject.dataset.subjectId}"]`);
                if (clone) {
                    clone.classList.remove(COLOR_DEFAULT, COLOR_ALTERNATE, COLOR_CONFLICT_1, COLOR_CONFLICT_2);
                    clone.classList.add(colorClass);
                }
                currentCell = getNextCell(currentCell);
            }
        }

        /**
         * Reassign colors to all subjects in the timetable.
         */
        function reassignAllColors() {
            const droppedSubjects = document.querySelectorAll('.dropped-subject:not(.cloned)');

            // first pass: Identify conflicting groups
            const conflictGroups = {};
            droppedSubjects.forEach(subject => {
                const startCell = subject.closest('td');
                const conflictingCourses = getConflictingCourses(startCell, parseInt(subject.dataset.length));
                if (conflictingCourses.length > 1) {
                    const key = conflictingCourses.map(c => c.dataset.subjectId).sort().join('-');
                    if (!conflictGroups[key]) {
                        conflictGroups[key] = conflictingCourses;
                    }
                }
            });

            // second pass: Assign colors
            droppedSubjects.forEach(subject => {
                const startCell = subject.closest('td');
                assignColorToSubject(subject, startCell);
            });

            // third pass: Ensure alternating colors for conflict groups
            Object.values(conflictGroups).forEach((group) => {
                group.forEach((subject, index) => {
                    const colorClass = index % 2 === 0 ? COLOR_CONFLICT_1 : COLOR_CONFLICT_2;
                    subject.classList.remove(COLOR_DEFAULT, COLOR_ALTERNATE, COLOR_CONFLICT_1, COLOR_CONFLICT_2);
                    subject.classList.add(colorClass);
                    colorAssignments[subject.dataset.subjectId] = colorClass;
                    applyColorToClones(subject, subject.closest('td'), parseInt(subject.dataset.length));
                });
            });
        }

        /**
         * Get all subjects that conflict with a subject being added to the timetable
         * @param startCell
         * @param length
         * @returns {*[]}
         */
        function getConflictingCourses(startCell, length) {
            let conflictingCourses = [];
            let currentCell = startCell;

            for (let i = 0; i < length && currentCell; i++) {
                const existingSubjects = Array.from(currentCell.querySelectorAll('.dropped-subject:not(.cloned)'));
                conflictingCourses = conflictingCourses.concat(existingSubjects);
                currentCell = getNextCell(currentCell);
            }

            // remove duplicates (in case of multi-cell subjects)
            return Array.from(new Set(conflictingCourses));
        }

        /*
         * -------- TIMETABLE DATA COLLECTION --------
         */

        function getTimetableState() {
            const timetableState = [];
            const droppedSubjects = document.querySelectorAll('.timetable .dropped-subject:not(.cloned)');

            droppedSubjects.forEach(subject => {
                const cell = subject.closest('td');
                const day = cell.cellIndex - 1; // Subtract 1 because the first column is for time
                const startTime = cell.dataset.time;
                const endTime = subject.dataset.endTime;
                const kursId = subject.dataset.subjectId;

                if (subject.dataset.stundeId) {
                    const stundeId = subject.dataset.stundeId;
                    timetableState.push({
                        kurs_id: kursId,
                        day: day,
                        start_time: startTime,
                        end_time: endTime,
                        stunde_id: stundeId
                    });
                } else {
                    timetableState.push({
                        kurs_id: kursId,
                        day: day,
                        start_time: startTime,
                        end_time: endTime
                    });
                }
            });

            return timetableState;
        }

        function updateTimetableState() {
            const state = getTimetableState();
            document.getElementById('timetableState').value = JSON.stringify(state);
        }

        document.getElementById('timetableForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    showAlert(data.message, data.type);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Fehler beim Speichern des Stundenplans.', 'error');
                });
        });
    }
});
