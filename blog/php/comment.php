<?php
// Required php
require('./lib/DB.class.php');
require('./lib/forms.php');

$db = new database();

// Field names of the form
$field_names = array('post_id', 'name', 'comment');

// Check required input
if(!checkInputs($field_names)) {
    header('Location: index.php');
    exit();
} else {
    init($db);
}

// Init php
function init($db) {
    $response = array();
    // Check place the user is reviewing exists
    if(!checkExists($db, $_POST['post_id'])) {
        // If not, return with failed status and error message.
        $response = array(
            'status' => 0,
            'msg' => 'The post you are commenting on does not exist'
        );
    } else {
        // If it does then create form data
        $form_data = array(
            'name' => array(
                'data' => $_POST['name'],
                'type' => 'form',
                'required' => true,
                'max-length' => 200,
                'min-length' => 1
            ),
            'comment' => array(
                'data' => $_POST['comment'],
                'type' => 'form',
                'required' => true,
                'max-length' => 500,
                'min-length' => 5
            ),
            'postid' => array(
                'data' => $_POST['post_id'],
                'type' => 'form',
                'required' => true,
                'max-length' => null,
                'min-length' => null
            ),
            'postedon' => array(
                'data' => date("Y-m-d H:i:s"),
                'type' => 'server'
            )
        );

        // Valid form data
        $checkedData = checkDataValid($form_data);

        // If data valid
        if($checkedData['valid']) {
            // Insert data into review table
            $sql = 'INSERT INTO comments (post_id, name, content, posted_on) VALUES (:postid, :name, :comment, :postedon)';
            if($db->process($form_data, $sql)) {
                // Give response with success status and message
                $response = array(
                    'status' => 1,
                    'msg' => "Your comment has been submitted! It is now awaiting approval. Thanks!",
                    'post_id' => $form_data['postid']['data']
                );
            }
        } else {
            // If failed, set form_response to failed data to pass back to form
            $_SESSION['form_response'] = $checkedData;
            // Fail with failed status 2, and the invalid data
            $response = array(
                'status' => 2,
                'data' => $checkedData,
                'post_id' => $form_data['postid']['data']
            );
        }
    }

    // Encode response to json
    $responseJSON = json_encode($response);
    echo $responseJSON;
}


// Check place exists
function checkExists($db, $post_id) {
    // SQL to select place by id
    $sql = 'SELECT * FROM posts WHERE id = :post_id';
    // Input data to select place
    $input = array(
        'post_id' => array(
            'data' => $post_id
        )
    );

    // Returns true if place exists, false if does not
    return $db->exists($input, $sql);
}
