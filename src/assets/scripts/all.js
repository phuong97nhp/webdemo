// $(document).ready(function() {

//     var getUrl = 'http://localhost:8081/api.php?value=get';
//     var deleteUrl = 'http://localhost:8081/api.php?value=delete';
//     var setUrl = 'http://localhost:8081/api.php?value=set';

//     $('#button-content').on('click', function() {
//         var getContent = $('#input-content').val();
//         if (getContent !== '') {

//             $.ajax({
//                 url: 'http://localhost:8081/api.php?value=set',
//                 type: 'POST',
//                 data: { 'content': getContent },
//                 dataType: 'json',
//                 success: function(data) {
//                     if (data.constructor === String) {
//                         data = JSON.parse(data);
//                     }

//                     if (data.status) {
//                         numberRestEmailFooter = data.rest_email;
//                         $('#numberRestEmailFooter').text(data.rest_email)
//                     }
//                 }
//             });

//             var contentHtml = '<li class="sub-item"><div class="item-content item">' + getContent + '</div><a href="javascript:void(0);" class="item-button item  d-content">Done</a><a href="javascript:void(0);" class="item-button item  delete-content">Delete</a></li>';
//             $('body').find('#content-output').prepend(contentHtml);
//             $('#input-content').val('');
//             $('#input-content').focus();
//         }
//     });

//     $("body").delegate('.delete-content', 'click', function(event) {
//         $(this).parent('.sub-item').remove();
//     });

//     $("body").delegate('.d-content', 'click', function(event) {
//         var getContentButton = $(this).text();
//         if (getContentButton == 'Done') {
//             $(this).text('Undone');
//         } else {
//             $(this).text('Done');
//         }
//     });
// });


var getUrl = 'http://localhost:8081/api.php?value=get';
var deleteUrl = 'http://localhost:8081/api.php?value=delete';
var setUrl = 'http://localhost:8081/api.php?value=set';
var doneUrl = 'http://localhost:8081/api.php?value=done';

function onClickEven(elSelector, eventName, selector, fn) {
    var element = document.querySelector(elSelector);

    element.addEventListener(eventName, function(event) {
        var possibleTargets = element.querySelectorAll(selector);
        var target = event.target;

        for (var i = 0, l = possibleTargets.length; i < l; i++) {
            var el = target;
            var p = possibleTargets[i];

            while (el && el !== element) {
                if (el === p) {
                    return fn.call(p, event);
                }

                el = el.parentNode;
            }
        }
    });
}


// onload todo
fetch(getUrl)
    .then(response => response.json())
    .then(data => functionList(data));

function functionList(data) {
    var insertHTML = document.getElementById("content-output");
    var textHtml = '';
    for (const d of data.data) {
        var $valueStatus;
        if (d.done == 'done') {
            $valueStatus = "Done";
        } else {
            $valueStatus = "Undone";
        }

        textHtml += '<li class="sub-item" idValue="' + d.id + '"><div class = "item-content item"> ' + d.content + '</div><a href="javascript:void(0);" class="item-button item  d-content">' + $valueStatus + '</a ><a href = "javascript:void(0);" class = "item-button item  delete-content"> Delete </a></li>';
    }
    if (textHtml !== "") {
        insertHTML.innerHTML = textHtml;
    }
}


// thay doi 1 element
onClickEven('#content-output', 'click', '.d-content', function myFunction() {
    var idValue = this.parentElement.getAttribute('idValue');
    fetch(doneUrl + '&id=' + idValue)
        .then(response => response.json())
        .then(data => functionDone(data, this));
});

function functionDone(data, myThis) {
    if (data.success === true) {
        if(data.done === "done"){
            var done = "Done";
        }else{
            var done = "Undone";
        }
        myThis.textContent = done;
        alert(data.messenger);
    } else {
        alert(data.messenger);
    }
}

// xóa 1 element
onClickEven('#content-output', 'click', '.delete-content', function() {
    var idValue = this.parentElement.getAttribute('idValue');
    var r = confirm("Bạn có chắc chắn là xóa dữ liệu này không ?");
    if (r == true) {
        fetch(deleteUrl + '&id=' + idValue)
            .then(response => response.json())
            .then(data => functionDelete(data, this));
    }
});

function functionDelete(data, myThis) {
    if (data.success == true) {
        myThis.parentElement.remove();
        alert(data.messenger);
    }else {
        alert(data.messenger);
    }
}

// thêm 1 element
var button = document.getElementById("button-content");
button.addEventListener('click', functionAdd, false);

function functionAdd() {
    var value = document.getElementById("input-content");

    if (value.value !== "") {
    const data = { "content": value.value };

    fetch(setUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success == true) {
                alert(data.messenger);
                if(data.data.id != ""){
                    var insertHTML = document.getElementsByClassName("sub-item");
                    var elementHTML = document.createElement('li');
                    elementHTML.setAttribute('idValue', data.data.id );
                    elementHTML.innerHTML = '<div class = "item-content item"> ' + data.data.content + ' </div><a href="javascript:void(0);" class="item-button item  d-content">' + data.data.done + '</a ><a href = "javascript:void(0);" class = "item-button item  delete-content" > Delete </a>';
                    elementHTML.className = "sub-item";
                    insertHTML[0].parentNode.insertBefore(elementHTML, insertHTML[0]);
                }
            }else{
                alert(data.messenger);
            }
        })
        .catch((error) => {
            if (data.success == false) {
                alert(data.messenger);
            }
        });

    }
    value.value = "";
    value.focus();
}