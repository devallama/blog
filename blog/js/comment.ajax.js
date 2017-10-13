// Event to call ajaxRequest on form submit

let comment_forms = document.getElementsByClassName('comment_form');
for(let i = 0; i < comment_forms.length; i++) {
    comment_forms[i].addEventListener("submit", ajaxRequest);
}
// document.getElementsByClassName('comment_form').onsubmit = function(e) {
//     console.log("called");
//     // Prevent the default action of form submit
//     e.preventDefault();
//     ajaxRequest(e);
// }

// Ajax request to enter a review
function ajaxRequest(e) {
    e.preventDefault();
    console.log(e);

    const post_id = e.target.querySelectorAll('[name=post_id]')[0].value;
    const name = e.target.querySelectorAll('[name=name]')[0].value;
    const comment = e.target.querySelectorAll('[name=comment]')[0].value;

    let xhr2 = new XMLHttpRequest();

    // URL of the php script
    const url = './php/comment.php';
    // Post data for the request
    const params = 'post_id=' + post_id + '&name=' + name + '&comment=' + comment;

    xhr2.addEventListener('load', response);

    // Sends the AJAx request
    xhr2.open('POST', url, true)
    xhr2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr2.send(params);
}

// Response from the ajaxrequest
function response(e) {
    // decode json response from php script
    let data = JSON.parse(e.target.responseText);

    let form = document.querySelectorAll('[data-postid="' + data['post_id'] + '"]')[0];
    // if successful then
    if(data['status'] == 1) {
        // Update review_reponse element to show sucess message
        form.getElementsByClassName('form_response')[0].innerHTML = data['msg'];
        // Hide any error message from previous
        form.getElementsByClassName('form_error')[0].style.display = "none";

    } else if(data['status'] == 0) {
        // If fails then show error message in element
        // form.getElementsByClassName('form_error')[0].innerHTML = data['msg'];
        alert(data['msg']);
        form.getElementsByClassName('form_error')[0].style.display = "block";
    } else if(data['status'] == 2) {
        // if fails with status 2, show the error message in the error element
        form.getElementsByClassName('form_error')[0].innerHTML = data['data']['failed_fields'][0][1];
        // add the users previously submitted review back into the textarea element so they do not lose their work
        form.querySelectorAll('[name=name]')[0].value = data['data']['data']['name']['data'];
        form.querySelectorAll('[name=comment]')[0].value = data['data']['data']['comment']['data'];
        form.getElementsByClassName('form_error')[0].style.display = "block";
    }
}
