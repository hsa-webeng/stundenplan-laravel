document.addEventListener('DOMContentLoaded', () => {

    const COLOR_DEFAULT = 'color-1';
    const COLOR_ALTERNATE = 'color-2';
    const COLOR_CONFLICT_1 = 'color-3';
    const COLOR_CONFLICT_2 = 'color-4';

    const timetableCells = document.querySelectorAll('.timetable td');

    let colorAssignments = {};

    reassignAllColors();

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
});
