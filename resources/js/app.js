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

        if (isValidDropTarget(this, subjectLength)) {
            const newSubject = draggedSubject.cloneNode(true);
            newSubject.classList.add('dropped-subject');
            newSubject.dataset.startTime = startTime;
            newSubject.dataset.endTime = endTime;
            newSubject.dataset.day = day;
            newSubject.dataset.subjectId = draggedSubject.dataset.subjectId;

            let timeSpan = newSubject.querySelector('.time-span');
            if (!timeSpan) {
                timeSpan = document.createElement('div');
                timeSpan.classList.add('time-span');
                newSubject.insertBefore(timeSpan, newSubject.firstChild);
            }
            timeSpan.textContent = `${startTime} - ${endTime}`;

            this.appendChild(newSubject);
            makeDraggable(newSubject);

            const originalParentCell = draggedSubject.parentNode;
            if (originalParentCell !== this) {
                removeSubjectAndClones(originalParentCell, parseFloat(draggedSubject.dataset.length));
            }

            let currentCell = this;
            for (let i = 1; i < subjectLength; i++) {
                currentCell = getNextCell(currentCell);
                if (currentCell) {
                    const clone = newSubject.cloneNode(true);
                    clone.classList.add('cloned');
                    clone.dataset.startTime = startTime;
                    clone.dataset.endTime = endTime;
                    clone.dataset.day = day;
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
     * @param {HTMLElement} cell - The current cell.
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
        const parentCell = subjectElement.parentNode;
        parentCell.removeChild(subjectElement);

        let currentCell = parentCell;
        const subjectLength = parseFloat(subjectElement.dataset.length);
        for (let i = 1; i < subjectLength; i++) {
            currentCell = getNextCell(currentCell);
            if (currentCell) {
                // unblock the cell
                currentCell.classList.remove('blocked');
                // remove any cloned elements from the cell
                const clonedElement = currentCell.querySelector('.cloned');
                if (clonedElement) {
                    currentCell.removeChild(clonedElement);
                }
            }
        }
    }

    /**
     * Removes a subject and its clones from a cell and unblocks the cells they occupied.
     * @param {HTMLElement} cell - The cell to remove the subject and its clones from.
     * @param {number} length - The length of the subject.
     */
    function removeSubjectAndClones(cell, length) {
        let currentCell = cell;
        let removedSubjects = 0;

        while (removedSubjects < length && currentCell) {
            const subject = currentCell.querySelector('.dropped-subject, .cloned');
            if (subject) {
                currentCell.removeChild(subject);
                currentCell.classList.remove('blocked');
                removedSubjects++;
            }
            currentCell = getNextCell(currentCell);
        }
    }

    function addFieldsetForCourse() {
        const formTemplate = document.getElementById('course-form-template').content.cloneNode(true);
        /*
         * clones a fieldset with the following inputs:
         * subject_id - id of the course (int - min 0)
         * start_time - start time of the course (HH:MM - min 08:00, max 21:00)
         * day - day of the week the course is on
         */

    }
});
