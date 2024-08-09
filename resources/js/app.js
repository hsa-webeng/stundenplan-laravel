import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    // select the timetable, all subjects, and all timetable cells
    const timetable = document.querySelector('.timetable');
    const subjects = document.querySelectorAll('.subject');
    const timetableCells = document.querySelectorAll('.timetable td');

    // make each subject draggable.
    subjects.forEach(subject => {
        makeDraggable(subject);
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
        const { startTime, endTime, day } = getTimeAndDayFromCell(this, subjectLength);

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

            if (newSubject === null) {
                return;
            }

            // Update the subject's time and day information
            newSubject.dataset.startTime = startTime;
            newSubject.dataset.endTime = endTime;
            newSubject.dataset.day = day.toString();

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
            for (let i = 1; i < subjectLength; i++) {
                currentCell = getNextCell(currentCell);
                if (currentCell) {
                    const clone = newSubject.cloneNode(true);
                    makeUnDraggable(clone)
                    clone.classList.add('cloned');
                    currentCell.appendChild(clone);
                } else {
                    break;
                }
            }

            highlightOriginal(newSubject);
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
        return { startTime: startTimeText, endTime: endTimeText, day };
    }

    /**
     * Calculates the end time for a subject given its start time and length.
     * @param {string} startTime - The start time of the subject.
     * @param {number} length - The length of the subject.
     * @returns {string} - The end time of the subject.
     */
    function calculateEndTime(startTime, length) {
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
     * Highlights the original subject element and removes the highlight from all other subjects.
     * @param {HTMLElement} subjectElement - The subject element to highlight.
     */
    function highlightOriginal(subjectElement) {
        subjects.forEach(subject => {
            subject.classList.remove('highlighted');
        });
        subjectElement.classList.add('highlighted');
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

        // enable draggable for the original subject
        const originalSubject = document.querySelector(`.subject[data-subject-id="${subjectId}"]`);
        makeDraggable(originalSubject);
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
});
