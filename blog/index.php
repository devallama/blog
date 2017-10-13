<?php
    require('./php/fetch_data.php');
    $posts = fetchAllPosts($db);
    $allComments = fetchAllComments($db);
    $comments = [];
    foreach($posts as $post) {
        $comments[$post['id']] = array();
        foreach($allComments as $comment) {
            if($post['id'] == $comment['post_id']) {
                array_push($comments[$post['id']], $comment);
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="../resources/css/style.css"/>
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200i,300,300i,400,400i" rel="stylesheet">
        <link rel="shortcut icon" href="../resources/imgs/favicon.ico" />
        <title>My Blog</title>
        <meta name="author" content="Nicholas Neale">
        <meta name="description" content="I'm Nic, I'm a Website Designer and Developer. This is my blog.">
        <meta name="keywords" content="HTML, CSS, JavaScript, Porfolio, Nicholas, Neale, Web, Design, Development, Node, Solent, University, Southampton, Blog">
    </head>
<body>
    <div id="wrap">
        <header>
            <div id="social_nav">
                <a href="https://github.com/nickallama" target="_blank" title="My GitHub"><img src="../resources/imgs/github.png" alt="GitHub Logo"></a>
                <a href="https://www.linkedin.com/in/nicholas-neale-ba4b7812a/" target="_blank" title="My Linkedin"><img src="../resources/imgs/linkedin.png" alt="Linkedin Logo"></a>
                <a href="https://twitter.com/nic_neale" target="_blank" title="My Twitter"><img src="../resources/imgs/twitter.png" alt="Twitter Logo"></a>
            </div>
        </header>
        <div id="title">
            <h1>Nic's Blog</h1>
            <nav>
                <ul>
                    <li>
                        <a href="/index.html">home</a>
                    </li>
                    <li>
                        <a href="/blog/">blog</a>
                    </li>
                </ul>
            </nav>
        </div>
        <main id="blog" class="row">
            <div id="welcome">
                <h2>Hi, I'm Nic..</h2>
                <h3>..and this is my blog where I post anything to do with Web Design and Development that I'm interested in.</h3>
            </div>
                <?php
                    if(count($posts) == 0) {
                        ?>
                        <p>
                            No posts to display.
                        </p>
                        <?php
                    } else {
                        foreach($posts as $key => $post) {
                            ?>
                            <section class="col-7 offset-3">
                                <h2>
                                    <?php echo $post['title']; ?>
                                </h2>
                                <div class="datetime">
                                    Posted on: <?php echo $post['posted']; ?>
                                </div>
                                <p>
                                    <?php echo $post['content']; ?>
                                </p>
                            </section>
                            <aside class="col-4 offset-1">
                                <div id="comments">
                                    <h3>Comments</h3>
                                    <?php
                                        if(count($comments[$post['id']]) == 0) {
                                            echo '<p>No comments</p>';
                                        } else {
                                            foreach($comments[$post['id']] as $key => $comment) {
                                                ?>
                                                <div class="comment">
                                                    <div class="content">
                                                        <?php echo $comment['content']; ?>
                                                    </div>
                                                    <div class="commenter">
                                                        By <span class="color_secondary"><?php echo $comment['name']; ?></span> on <?php echo $comment['posted_on']; ?>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                    ?>
                                </div>
                                <hr >
                                <form class="comment_form" data-postid="<?php echo $post['id']; ?>">
                                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                    <h4>Leave a comment.</h4>
                                    <div class="form_response"></div>
                                    <div class="form_error"></div>
                                    <label>Your name:</label>
                                    <input type="text" name="name"/>
                                    <label>Your comment:</label>
                                    <textarea name="comment"></textarea>
                                    <input type="submit" onsubmit="ajaxRequest();"/>
                                </form>
                            </aside>
                            <?php
                        }
                    }
                 ?>
        </main>
    </div>
    <script src="./js/comment.ajax.js"></script>
</body>
</html>
