let API = {
    'url' : '',  //http://localhost:8888
    'user' : { 
        auth:"", 
        name:"Anonymous"
    },
    'pendingTasksContainer' : 'pendingTasks',
    'finishedTasksContainer': 'finishedTasks'
};

function init(skipEvents) {
    if(!skipEvents) {
        newTaskButton = document.getElementById('task_new').querySelector('button');
        newTaskButton.addEventListener('click', function() {
            task_add(newTaskButton.parentNode);
        });
    }
    checkLogin();
    if (API.user.auth) {
        getTasks();
    }
}

function checkLogin() {
    if (!API.user.auth) {
        user = localStorage.getItem('user');
        if (user) {
            user = JSON.parse(user);
            if (user.auth && user.name) {
                API.user = user;
            }
        }
    }
    body = document.getElementById('body');
    if (API.user.auth) {
        localStorage.setItem('user', JSON.stringify(API.user));
        el = document.getElementById('welcomeBox');
        el.innerHTML = el.innerHTML.replace(/{{name}}/g, API.user.name);
        body.classList.add('logged');
        body.classList.remove('notLogged');
    } else {
        body.classList.add('notLogged');
        body.classList.remove('logged');
    }
}

function getTasks() {
    
    let pendingTasksContainer = document.getElementById(API.pendingTasksContainer);
    let finishedTasksContainer = document.getElementById(API.finishedTasksContainer);

    pendingTasksContainer.innerHTML = "<span class='loading'>Loading <em>pending</em> tasks, please wait...</span>";
    finishedTasksContainer.innerHTML = "<span class='loading'>Loading <em>finished</em> tasks, please wait...</span>";
    
    let xHttp = new XMLHttpRequest();
    xHttp.onreadystatechange = function() {
        if (this.readyState === 4) {
            let tasks = JSON.parse(this.responseText);
            if (this.status !== 200) {
                alert("Error " + tasks.error + " : " + tasks.message + "\nPlease retry!");
                return;
            }

            pendingTasksContainer.innerHTML = '';
            finishedTasksContainer.innerHTML = '';
            
            for (var i = 0; i < tasks.length; i++) {
                let el = document.createElement('div');
                el.id = 'task'+tasks[i]['id'];
                el.className = 'task_container';
                if(tasks[i]['status'] === 'finished') {
                    el.innerHTML = taskElement(tasks[i], 'finishedTaskTemplate');
                    finishedTasksContainer.appendChild(el);
                } else {
                    el.innerHTML = taskElement(tasks[i], 'pendingTaskTemplate');
                    pendingTasksContainer.appendChild(el);
                }
            }
        }
    };
    xHttp.open('GET', API.url + '/task', true);
    xHttp.setRequestHeader('Authorization', API.user.auth);
    xHttp.send();
}


function getTask(id) {
    var xHttp = new XMLHttpRequest();
    xHttp.onreadystatechange = function() {
        if (this.readyState === 4) {
            let task = JSON.parse(this.responseText);
            if (this.status !== 200) {
                alert("Error " + task.error + " : " + task.message + "\nPlease retry!");
                return;
            }
            divTaskUpdate(id, task);
            editable(id, false);
        }
    };
    xHttp.open('GET', API.url + '/task/'+id, true);
    xHttp.setRequestHeader('Authorization', API.user.auth);

    xHttp.send();
}

function editable(id, add) {
    let element = document.getElementById("task"+id);


    if (add) {
        editableElements = element.querySelectorAll("[contenteditable=false]");
        element.classList.add("editable");
    } else {
        editableElements = element.querySelectorAll("[contenteditable=true]");
        element.classList.remove("editable");
    }
    for(let i=0; i<editableElements.length; i++)
        editableElements[i].setAttribute("contenteditable", add);
}

function task_edit(id) {
    editable(id, true);
}
function task_delete(id) {
    if(!confirm('Are you sure?')){
        return;
    }
    var xHttp = new XMLHttpRequest();
    xHttp.onreadystatechange = function() {
        if (this.readyState === 4) {
            let response = JSON.parse(this.responseText);
            if (this.status !== 200) {
                alert("Error " + response.error + " : " + response.message + "\nPlease retry!");
                return;
            }
            document.getElementById('task' + id).remove();
        }
    };
    xHttp.open('DELETE', API.url + '/task/' + id, true);
    xHttp.setRequestHeader('Authorization', API.user.auth);
    xHttp.send();
}

function task_done(id) {
    sendUpdate(id, ['status=finished']);
}
function task_save(id) {
    let container = document.getElementById("task" + id);
    let editableElements = container.querySelectorAll("[contenteditable=true]");
    data = [];
    err = [];
    for (let i = 0; i < editableElements.length; i++) {
        key = editableElements[i].getAttribute('rel');
        val = editableElements[i].textContent.trim();
        if(!val) {
            err.push(key + ' is missing');
        } else {
            if (key==='date' && !isValidDate(val)) {
                err.push('Date is invalid, use YYYY-MM-DD format');
            }
        }
        data.push(
            encodeURIComponent(key)
            + '='
            + encodeURIComponent(val)
        );
    }
    if(err.length) {
        alert(err.join("\n"));
        return null;
    }
    if(!confirm('Are you sure you want to keep the changes?')){
        return;
    }
    sendUpdate(id, data);
    editable(id, false);
}

function sendUpdate(id, data, callFunct) {
    let xHTTP = new XMLHttpRequest();
    xHTTP.onreadystatechange = function() {
        if (this.readyState === 4 ) {
            let task = JSON.parse(this.responseText);
            let status = id ? 200 : 201;
            if (this.status !== status) {
                alert("Error " + task.error + " : " + task.message + "\nPlease retry!");
            } else {
                if (id) {
                    divTaskUpdate(id, task);
                } else {
                    getTasks();
                }
                if (callFunct) {
                    callFunct();
                }
            }
        }
    };
    if(id) {
        xHTTP.open('PUT', '/task/' + id, true);
    } else {
        xHTTP.open('POST', '/task', true);
    }
    xHTTP.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded');
    xHTTP.setRequestHeader('Authorization', API.user.auth);
    xHTTP.send(data.join('&'));
}
function divTaskUpdate(id, task) {
    let taskEL = document.getElementById('task'+id);
    let parentEL = taskEL.parentNode;
    if (task.status === 'finished') {
        taskEL.innerHTML = taskElement(task, 'finishedTaskTemplate');
        if(parentEL.id !== 'finishedTasks') {
            let otherParentEL = document.getElementById('finishedTasks');
            otherParentEL.appendChild(taskEL);
        }
    } else {
        taskEL.innerHTML = taskElement(task, 'pendingTaskTemplate');
        if(parentEL.id !== 'pendingTasks') {
            let otherParentEL = document.getElementById('pendingTasks');
            otherParentEL.appendChild(taskEL);
        }
    }
}


function task_cancel(id) {
    if(!confirm('Are you sure you want to delete the changes?')){
        return;
    }
    getTask(id, false);
   
}

function task_undone(id) {
    sendUpdate(id, ['status=pending']);
}

function task_add(container) {
    let elements = container.getElementsByTagName('input');
    let data = serializeInputs(elements);
    if (!data) return;
    sendUpdate(null, data, function(){
        for (let i = 0; i < elements.length; i++) {
            elements[i].value = '';
        }      
    });
}
function logout() {
    API.user = 
    localStorage.removeItem('user');
}
function login(container) {
    let elements = container.getElementsByTagName('input');
    let data = serializeInputs(elements);
    if (!data) return;
    let xHTTP = new XMLHttpRequest();
    xHTTP.onreadystatechange = function() {
        if (this.readyState === 4 ) {
            let user = JSON.parse(this.responseText);
            if (this.status !== 200) {
                alert("Error " + user.error + " : " + user.message + "\nPlease retry!");
            } else {
                API.user.auth = user.auth;
                API.user.name = user.name;
                init(true);
            }
        }
    };

    xHTTP.open('POST', '/user/login', true);
    xHTTP.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded');
    xHTTP.send(data.join('&'));
}



function serializeInputs(elements) {
    let data = [];
    let err = [];
    for (let i = 0; i < elements.length; i++) {
         key =  elements[i].getAttribute('name');
         val = elements[i].value.trim();
        if(!val) {
            err.push(key + ' is missing');
        }
        else {
            if (key === 'date' && !isValidDate(val)) {
                err.push('Date is invalid');
            }
        }                       
        data.push(
            encodeURIComponent(key)
            + '='
            + encodeURIComponent(val)
        );
    }
    if(err.length) {
        alert(err.join("\n"));
        return null;
    }
    for (let i = 0; i < elements.length; i++) {
        elements[i].value = '';
    }
    return data;
}

function taskElement(task, elID) {
    let taskEl = document.getElementById(elID).innerHTML;


    return taskEl
        .replace(/{{id}}/g, task.id)
        .replace(/{{title}}/g, task.title)
        .replace(/{{date}}/g, task.date)
        .replace(/{{description}}/g, task.description)
    ;
    
}
function isValidDate(dateString)
{
    // First check for the pattern
    var regex_date = /^\d{4}-\d{2}-\d{2}$/;

    if(!regex_date.test(dateString))
    {
        return false;
    }

    // Parse the date parts to integers
    var parts   = dateString.split("-");
    var day     = parseInt(parts[2], 10);
    var month   = parseInt(parts[1], 10);
    var year    = parseInt(parts[0], 10);

    // Check the ranges of month and year
    if(year < 1000 || year > 3000 || month === 0 || month > 12)
    {
        return false;
    }

    var monthLength = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];

    // Adjust for leap years
    if(year % 400 === 0 || (year % 100 !== 0 && year % 4 === 0))
    {
        monthLength[1] = 29;
    }

    // Check the range of the day
    return day > 0 && day <= monthLength[month - 1];
}

