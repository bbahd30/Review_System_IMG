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
            if(tabName == "seeAssign")
            {
                document.querySelector("#itern").addEventListener("click", (e)=>
                {
                    e.preventDefault();
                    alert("This is under review, hence request can't be removed.");
                })
                document.querySelector("#passed").addEventListener("click", (e) =>
                {
                    e.preventDefault();
                })
            }
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
        document.querySelectorAll(".active").forEach
        (
            function(id)
            {
                id.classList.remove("active");
            }
        )
        if(tabName != tabNameGiven)
        {
            tabName = tabNameGiven;
            tabOpener(tabName);
        }
        idGiven.classList.add("active");
        
    });
}

tabChanger(assign, "seeAssign");
tabChanger(dash, "sDashboard");
tabChanger(requests, "requests");


