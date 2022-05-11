// Script wide variables
let userID = document.getElementById("userID").value; // UID
let select = document.getElementById("active-chats"); // Conversation Selection
let chatBox = document.getElementById("chat-box"); // Scroll pinned to bottom
let chatFill = document.getElementById("chat-fill"); // Message Box
let dirFill = document.getElementById("directory"); // Directory box
let msgSend = document.getElementById("msg-send"); // Msg input
let typeBox = document.getElementById("user-msg"); // User typing text area
let path = document.getElementById("file-path"); // file path
let contact = []; // Arr of all active chat UIDs
let users = []; // Arr of all active chat usernames
let split = ""; // Talking To
let lastTS = ""; // Last timestamp in chat
let hasDirs = 0; // To prevent event listeners on non-existent
let fileSelected = 0;
let dirBtns; // Event Listeners added when hasDirs = 1;
let tmrNewMsg; // timer once chat loads to check for new messages
let tmrTypingA; // timer to update that I am typing
let tmrTypingB; // timer to check other person is typing

// Events Listeners ~
window.addEventListener("load", function (){getContacts()}, false);
msgSend.addEventListener("click", function () {msgHandler()}, false);
typeBox.addEventListener("keydown", function () {userTyping()}, false);
select.addEventListener("change", function ()
{
    getConversation(this.value);
    clearInterval(tmrNewMsg);
    clearInterval(tmrTypingA);
    clearInterval(tmrTypingB);
    tmrNewMsg = setInterval(updateHandler, 3000);
    tmrTypingA = setTimeout(userNotTyping, 1800);
    tmrTypingB = setInterval(getUserTyping, 1800);
}, false);
path.addEventListener("change", function ()
{
    fileSelected = 1; // pre-check
}, false);

// Functions
function userNotTyping()
{
    let xhr = new XMLHttpRequest();
    xhr.open("get", "liveTypingAF.php?a=" + userID, true);
    xhr.send();
}

function userTyping()
{
    clearTimeout(tmrTypingA);
    let xhr = new XMLHttpRequest();
    xhr.open("get", "liveTypingAT.php?a=" + userID, true);
    xhr.send();
    tmrTypingA = setTimeout(userNotTyping, 4000);
}

function getUserTyping()
{
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function ()
    {
        if (this.status === 200 && this.readyState === 4)
        {
            let typing = document.getElementById("typing"); // user is typing msg
            let arr = JSON.parse(xhr.responseText);
            if(arr[0] === '1')
            {
                console.log("Typing");
                typing.innerHTML = split[1] + " is typing...";
                typing.disabled = false;
            }else{
                console.log("Not Typing");
                typing.innerText = "";
            }
        }
    }
    xhr.open("get", "liveTypingB.php?b=" + split[0], true);
    xhr.send();
}

function createConv(id)
{
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function ()
    {
        if (this.status === 200 && this.readyState === 4)
        {
            getContacts();
        }
    }
    xhr.open("get", "liveNewConvo.php?a=" + userID + "&b="+ id, true);
    xhr.send();
}

function setDirBtnListeners()
{
    if(hasDirs === 0)
    {}else{
        dirBtns = document.getElementsByClassName("dir-btn");
        Array.from(dirBtns).forEach(function (btn)
        {
            btn.addEventListener("click", function () {createConv(this.value)}, false);
        });
    }
}

function getDirectory()
{
    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function ()
    {
        if (this.status === 200 && this.readyState === 4)
        {
            dirFill.innerHTML = "";

            let newArr = JSON.parse(xhr.responseText);
            newArr.forEach(function (obj)
            {
                let nObj = new Contact(obj);

                if(contact.includes(nObj.userID))
                {}else{
                    let dirEnt = new DirEntry(nObj.userID, nObj.username);
                    dirFill.appendChild(dirEnt);
                    hasDirs = 1;
                }
            });
            setDirBtnListeners();
        }
    }
    xhr.open("get", "liveDirectory.php?u=" + userID, true);
    xhr.send();
}

// HANDLES NEW MESSAGES ON ACTIVE CHAT
function updateHandler()
{
    if(split === "")
    {
        clearInterval(tmrNewMsg);
    }else{
        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function ()
        {
            if (this.status === 200 && this.readyState === 4)
            {
                let arr = JSON.parse(xhr.responseText);

                if(arr === null)
                {}else{
                    arr.forEach(function (obj)
                    {
                        appendMsg(obj);
                    });
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            }
        }
        xhr.open("get", "liveChatUpdate.php?a="+userID+"&b="+split[0]+"&t="+ lastTS, true);
        xhr.send();
    }
}

// HANDLES SENDING MESSAGES TO ACTIVE CHAT
function msgHandler()
{
    // Textarea behaviour required value call twice
    let msgBody = document.getElementById("user-msg").value;

    if(msgBody === "" || split === "")
    {}else{
        let form = new FormData();
        form.append("msg_body", msgBody);
        form.append("user_from", userID);
        form.append("user_to", split[0]);

        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function()
        {
            if(xhr.readyState == 4 && xhr.status == 200)
            {
                clearInterval(tmrNewMsg);
                tmrNewMsg = setInterval(updateHandler, 2200);
                typeBox.value = "";
            }
        }
        xhr.open("post", "liveChatSend.php", true);
        xhr.send(form);
    }
    updateHandler();
}

// GETS ALL PREVIOUSLY CONTACTED ON PAGE LOAD - CALL TO GET NOT CONTACTED
function getContacts()
{
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function ()
    {
        if (this.status === 200 && this.readyState === 4)
        {
            select.innerHTML = "";
            contact = [];
            let arr = JSON.parse(xhr.responseText)

            let placeholder = document.createElement("option");
            placeholder.innerHTML = "Select Active Chat(s)";
            placeholder.disabled = true;
            placeholder.selected = true;
            select.appendChild(placeholder);

            arr.forEach(function (obj)
            {
                let opt = document.createElement("option"); // Active Chat Selection
                let nObj = new Contact(obj);
                contact.push(nObj.userID);
                let temp = nObj.userID + "," + nObj.username;
                users.push(temp); // for notifications
                opt.value =  temp;
                opt.innerHTML = nObj.username;
                select.appendChild(opt);
            });
            getDirectory();
        }
        // getNotifications(); // getNotifications should be placed around here
    }
    xhr.open("GET", "liveContacts.php?c=" + userID, true);
    xhr.send();
}

// GETS CONVERSATION HISTORY UPON OPENING ACTIVE CHAT - STARTS TIMER
function getConversation(val)
{
    split = val.split(",");
    document.getElementById("chat-to").innerHTML = split[1];

    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function ()
    {
        chatFill.innerHTML = "";
        if (this.status === 200 && this.readyState === 4)
        {
            let arr = JSON.parse(xhr.responseText);

            arr.forEach(function (obj)
            {
                appendMsg(obj);
            });
            chatBox.scrollTop = chatBox.scrollHeight;
            updateHandler();
        }
    }
    xhr.open("GET", "liveConverse.php?f=" + userID + "&t=" + split[0], true);
    xhr.send();
}

function appendMsg(obj){
    let msg = new Message(obj);
    let msgBox = new MessageBox(obj.u, obj.mb, obj.ts);
    chatFill.appendChild(msgBox);
    lastTS = msg.ts;
}

// Constructs for easier Data Handling
function DirEntry(id, usr)
{
    // Creating all required elems - ordered according to final structure
    this.divA = document.createElement("div");
    this.p1 = document.createElement("p"); // username
    this.frm = document.createElement("form");
    this.btn = document.createElement("button");

    // Set information
    this.divA.className = "contact light-blue lighten-5 col s12 l4 center-align";
    this.p1.className = "dir-name";
    this.p1.innerHTML = usr;
    this.frm.className = "center-align";
    this.frm.method = "post";
    this.frm.action = "";
    this.btn.className = "btn-small cont-btn black dir-btn";
    this.btn.type = "button";
    this.btn.value = id;
    this.btn.innerHTML = "Chat";

    // hierarchy

    this.divA.appendChild(this.p1);
    this.frm.appendChild(this.btn);
    this.divA.appendChild(this.frm);

    return this.divA;
}

function Contact(obj)
{
    this.userID = obj['userID'];
    this.username = obj['username'];
}

function Message(obj)
{
    this.u = obj['u']; // Username
    this.ts = obj['ts']; // timestamp
    this.isI = obj['isI']; // isImg

    if(this.isI == 0)
    {
        this.mb = obj['mb']; // msgBody
    }else{
        // code to structure image
        this.i = obj['i']; // img
    }
}

function MessageBox(from, body, time)
{
    // Creating all required elems - ordered according to final structure
    this.divA = document.createElement("div");
    this.p1 = document.createElement("p"); // Name
    this.p2 = document.createElement("p"); // Body
    this.p3 = document.createElement("p"); // Timestamp

    // Set information
    this.p1.className = "username";
    this.p2.className = "msg-body";
    this.p3.className = "timestamp";
    if(from == split[0])
    {
        this.divA.className = "them";
        this.p1.innerHTML = split[1];

    }else{
        this.divA.className = "me";
        this.p1.innerHTML = "You";
    }
    this.p2.innerHTML = body;
    this.p3.innerHTML = time;

    // Hierarchy
    this.divA.appendChild(this.p1);
    this.divA.appendChild(this.p2);
    this.divA.appendChild(this.p3);

    return this.divA;
}

/* ############### unimplemented functions - notify sync, img parse #######################
function getNotifications()
{
    Array.from(users).forEach(function (obj)
    {
        let split = obj.split(",");
        let xhr = new XMLHttpRequest();

        if (xhr.status === 200 && xhr.readyState === 4)
        {
            console.log("test");
        }

        xhr.open("get", "liveNotify.php?a=" + userID, true);
        xhr.send();
    });
}

//imgSend.addEventListener("click", function (){imgHandler()}, false); // let deleted
function imgHandler()
{
    let imgFile = document.getElementById("img-in").files;
    let imgData = new FormData();

    imgData.append("img", imgData); // only one

    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function()
    {
        if(xhr.readyState == 4 && xhr.status == 200)
        {
            clearInterval(tmrNewMsg);
            tmrNewMsg = setInterval(updateHandler, 2200);
            //let test = JSON.parse(xhr.responseText);
            console.log(xhr.responseText);
        }
    }
    xhr.open("post", "liveImgSend.php", true);
    xhr.send(imgData);
    updateHandler();
}*/