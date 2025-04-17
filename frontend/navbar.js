//referenced: https://stackoverflow.com/questions/70993254/how-do-i-create-a-js-file-that-creates-a-navigation-bar-in-html
//https://stackoverflow.com/questions/54924823/is-there-a-way-to-import-html-into-an-html-file
const path = window.location.pathname
var userInd = path.indexOf("/budget_buddy")
var userPath = path.substring(0, userInd)


const header= `
   <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="${userPath}/budget_buddy/frontend/index.html">BudgetBuddy</a>
            <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="nav-link" href="${userPath}/budget_buddy/frontend/home/index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="${userPath}/budget_buddy/frontend/login.html">Signout</a></li>
                </ul>
            </div>
        </div>
    </nav>`
   
document.querySelector("body").insertAdjacentHTML("afterbegin", header);

//add the active class to the anchor tag with the href === path
document.querySelector(`[href='${path}']`).classList.add('active')
