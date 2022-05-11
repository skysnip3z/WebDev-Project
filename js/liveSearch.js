// <input> element w/EventListener
let inputTxt = document.getElementById("searchBar");
inputTxt.addEventListener("keyup", function () {displaySearch(this.value)}, false);

function SearchResult(obj)
{
    this.postID = obj['postID'];
    this.subject = obj['subject'];
}

function displaySearch(search)
{
    let output = document.getElementById("live-add");

    if (search.length < 2)
    {}
    else {
        let xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function ()
        {
            if (this.status === 200 && this.readyState === 4)
            {
                // document.getElementById("live-remove").innerTEXT = "";
                let arr = JSON.parse(xhr.responseText);

                if(output.innerHTML != null)
                {
                    output.innerHTML = ""; // remove results if user types something else

                    arr.forEach(function (obj)
                    {
                        // For clarity and ease of use, time complexity tested
                        nObj = new SearchResult(obj);

                        let card = new CreateCard(obj.subject, obj.postID);
                        output.appendChild(card);
                    });
                }
            }
        }
        // Open for communication w/server, asynchronous
        xhr.open("GET", "liveSearch.php?s=" + search, true);
        xhr.send();
    }
}

function CreateCard(sub, pst)
{
    // Creating all required elems - ordered according to final structure
    this.divA = document.createElement("div");
    this.divB = document.createElement("div");
    this.divC = document.createElement("div");
    this.p = document.createElement("p"); // subject
    this.divD = document.createElement("div");
    this.form = document.createElement("form"); // POST
    this.input = document.createElement("input"); // hidden
    this.btn = document.createElement("button");
    this.i = document.createElement("i"); // icon

    // Setting up all elem tag content - ordered according to final structure
    this.divA.className = "col s12 m4";
    this.divB.className = "card small blue lighten-5";
    this.divC.className = "card-content black-text row";
    this.p.className = "col s12 l12";
    this.p.innerHTML = "Subject: " + sub;
    this.divD.className = "card-action col s12 l12";
    this.form.className = "center card-button";
    this.form.method = "post";
    this.form.action = "$_SERVER['PHP_SELF']";
    this.input.type = "hidden";
    this.input.name = "post_id";
    this.input.value = pst;
    this.btn.className = "btn-large black";
    this.btn.type = "submit";
    this.btn.innerHTML = "View Post";
    this.i.className = "material-icons right";
    this.i.innerHTML = "visibility";


    // Configuring proper elem hierarchy
    this.divA.appendChild(this.divB).appendChild(this.divC).appendChild(this.divD);
    this.divC.appendChild(this.p);
    this.divD.appendChild(this.form).appendChild(this.input);
    this.form.appendChild(this.btn);
    this.btn.appendChild(this.i);

    return this.divA;
}