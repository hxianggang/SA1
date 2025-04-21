document.addEventListener('DOMContentLoaded', function () {
    // 打開表單
    function openForm() {
        document.getElementById("formContainer").style.display = "block";
    }

    // 關閉表單
    function closeForm() {
        document.getElementById("formContainer").style.display = "none";
    }

    // 在全局範圍內暴露 `openForm` 和 `closeForm` 函數
    window.openForm = openForm;
    window.closeForm = closeForm;
});
