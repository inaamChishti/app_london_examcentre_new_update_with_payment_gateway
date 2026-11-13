(function () {
    "use strict";

    function normalizeForDuplicateCheck(text) {
        return String(text || "")
            .replace(/\s+/g, "")
            .toLowerCase();
    }

    function isNullLikeOption(optionText) {
        var trimmed = String(optionText || "").trim().toLowerCase();
        return trimmed === "" || trimmed === "null" || trimmed === "undefined";
    }

    function sanitizeSelectOptions(selectElement) {
        if (!selectElement) return;

        var options = Array.prototype.slice.call(selectElement.options);
        var seenOptionsByKey = new Map();
        var selectedValue = selectElement.value;

        options.forEach(function (option) {
            var text = option.textContent || "";
            var textTrimmed = text.trim();
            var value = String(option.value || "").trim();
            var isPlaceholder = value === "" && /^select\s+/i.test(textTrimmed);

            if (!isPlaceholder && isNullLikeOption(textTrimmed)) {
                option.remove();
                return;
            }

            var normalizedKey = normalizeForDuplicateCheck(textTrimmed);
            if (!normalizedKey) {
                option.remove();
                return;
            }

            if (seenOptionsByKey.has(normalizedKey)) {
                var existingOption = seenOptionsByKey.get(normalizedKey);

                // If duplicate appears and new one is selected, keep selected one.
                if (option.selected && !existingOption.selected) {
                    existingOption.remove();
                    seenOptionsByKey.set(normalizedKey, option);
                    return;
                }

                option.remove();
                return;
            }

            seenOptionsByKey.set(normalizedKey, option);
        });

        if (selectedValue && selectElement.querySelector('option[value="' + CSS.escape(selectedValue) + '"]')) {
            selectElement.value = selectedValue;
        }
    }

    function sanitizeExamBoardAndCourseType() {
        document.querySelectorAll("select.exam-board, select.course-type").forEach(function (selectElement) {
            sanitizeSelectOptions(selectElement);
        });
    }

    function runSanitizeWithDelay() {
        sanitizeExamBoardAndCourseType();
        setTimeout(sanitizeExamBoardAndCourseType, 0);
        setTimeout(sanitizeExamBoardAndCourseType, 50);
    }

    document.addEventListener("DOMContentLoaded", function () {
        runSanitizeWithDelay();

        var addRowButton = document.querySelector(".add-row-button");
        if (addRowButton) {
            addRowButton.addEventListener("click", function () {
                runSanitizeWithDelay();
            });
        }

        var tbody = document.querySelector("tbody");
        if (tbody) {
            var observer = new MutationObserver(function () {
                runSanitizeWithDelay();
            });

            observer.observe(tbody, {
                childList: true,
                subtree: true
            });
        }

        document.addEventListener("change", function (event) {
            if (event.target && (event.target.matches("select.exam-board") || event.target.matches("select.course-type"))) {
                runSanitizeWithDelay();
            }
        });
    });
})();
