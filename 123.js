document.addEventListener('DOMContentLoaded', function () {
    // 打開表單
    function openForm() {
        document.getElementById("formContainer").style.display = "block";
    }

    // 關閉表單
    function closeForm() {
        document.getElementById("formContainer").style.display = "none";
    }

    function openForm2() {
        document.getElementById("formContainer2").style.display = "block";
    }

    // 關閉表單
    function closeForm2() {
        document.getElementById("formContainer2").style.display = "none";
    }

    // 在全局範圍內暴露 `openForm` 和 `closeForm` 函數
    window.openForm = openForm;
    window.closeForm = closeForm;
    window.openForm2 = openForm2;
    window.closeForm2 = closeForm2;
});

function OpenFunc1() {
    const Func1 = document.getElementById("index_title_func_1");
    const Func2 = document.getElementById("index_title_func_2");
    Func1.classList.add("open");
    Func2.classList.remove("open");

    document.getElementById("in_progress_area").style.display = "flex";
    document.getElementById("reached_goal_area").style.display = "none";
}

function OpenFunc2() {
    const Func1 = document.getElementById("index_title_func_1");
    const Func2 = document.getElementById("index_title_func_2");
    Func2.classList.add("open");
    Func1.classList.remove("open");

    document.getElementById("in_progress_area").style.display = "none";
    document.getElementById("reached_goal_area").style.display = "flex";
}
