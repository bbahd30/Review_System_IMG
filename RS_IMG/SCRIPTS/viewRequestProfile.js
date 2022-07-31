var comment = document.querySelector("#comment");
var postBtn = document.getElementById("postBtn");
var commentInp = document.getElementById("newComment");

showChats = () =>
{
    var obj = new XMLHttpRequest();
    obj.onload = function()
    {
        if (obj.status == 200)
        {
            document.getElementById("commentSection").innerHTML = this.responseText;

        }
    }
    obj.open("POST","../PHP/commentSection.php", true);
    obj.send();
}

postComment = () =>
{
    var obj = new XMLHttpRequest();
    var newComment = document.getElementById("newComment").value;
    data = "newComment=" + newComment;
    obj.open("POST","../PHP/inserterAjax.php", true);

    obj.onload = function()
    {
        if (obj.status == 200)
        {
            showChats();
            document.getElementById("newComment").value = "";
        }
    }
    obj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    obj.send(data);
}

window.addEventListener("load", () =>
{
    setInterval
    (
        showChats, 5000000000
    );
});

commentInp.addEventListener("keypress", (event) =>
{
    if(event.key == "Enter")
    {
        postComment();
    }
})
postBtn.addEventListener("click", postComment)



