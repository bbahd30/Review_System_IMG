var tabName = "";
var assign = document.getElementById("assign");
var adder = document.getElementById("adder");
var dash = document.getElementById("dash");
var mainContainer = document.querySelector(".mainContainer");


invisibleMaker = () => 
{
    document.querySelectorAll(".parts").forEach
    (
        function(parts)
        {
            parts.classList.add("makeInvisible");
        }
    )  
}

tabOpener = (tabName) =>
{
    var obj = new XMLHttpRequest();
    obj.onload = function()
    {
        if (obj.status == 200)
        {
            invisibleMaker();
            mainContainer.innerHTML = this.responseText;
            document.querySelector("#"+tabName).classList.remove("makeInvisible");
            document.querySelector("#"+tabName).disabled = true;
        }
    }
    obj.open("POST","../PHP/"+tabName+"Tab.php", true);
    obj.send();
}


tabChanger = (idGiven, tabNameGiven) =>
{
    idGiven.addEventListener("click",() =>
    {
        if(tabName != tabNameGiven)
        {
            tabName = tabNameGiven;
            tabOpener(tabName);
        }

    });
}
tabChanger(assign, "manageAssign");
tabChanger(adder, "manageStudents");
tabChanger(dash, "rDashboard");
