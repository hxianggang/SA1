function SwitchL(){
    fun1 = document.getElementById("sl");
    fun2 = document.getElementById("sr");
    fun3 = document.getElementById("rd");
    fun4 = document.getElementById("ld");
    fun1.classList.add("on");
    fun2.classList.remove("on");
    fun3.classList.add("close");
    fun4.classList.remove("close");
}
function SwitchR(){
    fun1 = document.getElementById("sl");
    fun2 = document.getElementById("sr");
    fun3 = document.getElementById("rd");
    fun4 = document.getElementById("ld");
    fun2.classList.add("on");
    fun1.classList.remove("on");
    fun4.classList.add("close");
    fun3.classList.remove("close");
}
function OpenVote(){
    let vote = document.getElementById("vote-box");
    if (vote.classList.contains('open')){
        vote.classList.remove("open");
    } else {
        vote.classList.add("open");
    }
}
function CloseVote(){
    let vote = document.getElementById("vote-box");
    vote.classList.remove("open");
}
function VoteFin(){
    alert("你已完成投票");
    let vote = document.getElementById("vote-box");
    vote.classList.remove("open");
}

function Scroll(id, offsetPercent = 0.15){
    const target = document.getElementById(id);
    const offset = window.innerHeight * offsetPercent;
    const targetTop = target.getBoundingClientRect().top + window.scrollY; //滾動頁面
  
    window.scrollTo({
      top: targetTop - offset,
      behavior: 'smooth'
    });
}

const suggestions = [
    { id: 1, title: "改善圖書館照明", content: "晚上太暗，請增加燈光", status: "待審核", votes: 10 },
    { id: 2, title: "增加校園飲水機", content: "希望在操場附近增加飲水機", status: "處理中", votes: 5 }
];

function renderSuggestions() {
    const tableBody = document.getElementById("suggestionTable");
    tableBody.innerHTML = "";
    
    if (suggestions.length === 0) {
        tableBody.innerHTML = "<tr><td colspan='7'>目前沒有建言。</td></tr>";
        return;
    }

    suggestions.forEach(suggestion => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${suggestion.id}</td>
            <td>${suggestion.title}</td>
            <td>${suggestion.content}</td>
            <td>${suggestion.status}</td>
            <td>${suggestion.votes}</td>
        `;
        tableBody.appendChild(row);
    });
}

function approveSuggestion(id) {
    const suggestion = suggestions.find(s => s.id === id);
    if (suggestion) {
        suggestion.status = "已批准";
        renderSuggestions();
    }
}

function rejectSuggestion(id) {
    const suggestion = suggestions.find(s => s.id === id);
    if (suggestion) {
        suggestion.status = "已拒絕";
        renderSuggestions();
    }
}

function viewVotes(id) {
    const suggestion = suggestions.find(s => s.id === id);
    if (suggestion) {
        alert(`建言: ${suggestion.title}\n投票數: ${suggestion.votes}`);
    }
}

document.addEventListener("DOMContentLoaded", renderSuggestions);