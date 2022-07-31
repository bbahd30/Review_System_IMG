var index = 1; 
var iterns = document.querySelector("#addItern");
var iternNum = document.querySelector("#iternNum").value;

document.querySelector("#yes").addEventListener("click",() =>
{
    document.getElementById("itern").classList.remove    ("makeInvisible");
    // add to itern count and list all the changes.
})
document.querySelector("#no").addEventListener("click",() =>
{
   document.getElementById("itern").classList.add("makeInvisible");
})
// SO INDEX IS NUMBER OF CHANGES GIVEN

document.querySelector("#addBtn").addEventListener("click", () =>
{
    index = index + 1;
    changeDiv = document.createElement("div");
    changeDiv.setAttribute("class", "itern");
    changeDiv.innerHTML = "<label for='change"+index+"'>Add Change "+ index+ "</label><input type='text' name='change"+index+"' placeholder='Change "+index+"' class='newItern' id='change"+index+"' autocomplete = 'off'>";

    iterns.insertAdjacentElement("beforeend", changeDiv);

})

document.querySelector("#removeBtn").addEventListener("click", ()=>
{
    iterns.removeChild(iterns.lastChild);
    index--;
})

document.querySelector("#submitBtn").addEventListener("click", () =>
{
    if(document.querySelector("#yes").checked)
    {
        var obj = new XMLHttpRequest();
        
        data = "iternNumNow="+iternNum;
        obj.open("POST","../PHP/inserterAjax.php", true);
       
        obj.onload = function()
        {
            if (obj.status == 200)
            {
                // show that data updated
            }
        }

        obj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        obj.send(data);

        

        var obj2 = new XMLHttpRequest();
        
        data = "index="+index;
        obj2.open("POST","../PHP/manageItern.php", true);
       
        obj2.onload = function()
        {
            if (obj2.status == 200)
            {
                // show that data updated
                alert("second");
            }
        }

        obj2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        obj2.send(data);
    }
    else if(document.querySelector("#no").checked)
    {
        var obj = new XMLHttpRequest();
        
        obj.open("POST","../PHP/inserterAjax.php", true);
       
        obj.onload = function()
        {
            if (obj.status == 200)
            {
                // show that data updated
            }
        }

        obj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        obj.send("pass=true");
    }
})