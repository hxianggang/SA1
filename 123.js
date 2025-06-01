document.addEventListener('DOMContentLoaded', function () {
    // 打開表單
    window.openForm = function() {
        document.getElementById("formContainer").style.display = "block";
    }

    // 關閉表單(新增提議，下方選擇)
    window.closeForm = function (clear = false) {
        document.getElementById("formContainer").style.display = "none";
        if (clear) {
            document.getElementById('suggestionForm').reset(); // 清空表單
        }
    }
    window.confirmCancel = function() {
        const userChoice = confirm("你要清空資料還是保留？\n\n點擊『確定』：清空資料\n點擊『取消』：保留資料");

        if (userChoice) {
            // 使用者選擇清空
            closeForm(true);
        } else {
            // 使用者選擇保留
            closeForm(false);
        }
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
