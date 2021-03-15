<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/styles/all.css">
    <title>Website demo</title>
    <script>
        var page = "trang-chu";
    </script>
</head>
<body>
    <header id="headerContainer">
        <div class="headerContent">
            <h1>WEB DEMO</h1>
        </div>
        <div class="menuContent">
            <nav class="nav-header">
                <ul>
                    <li class="active"><a href="#">Trang chu</a></li>
                    <li><a href="#">Danh muc</a></li>
                    <li><a href="#">Tin tuc</a></li>
                    <li><a href="#">Gioi thieu</a></li>
                    <li><a href="/send.php">Gửi mail</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <section id="mainContainer">
        <div class="sidebarContent">
            <article class="subSidebar"> 
                <div class="sub-sidebar-title">
                    <p>Danh muc</p>
                </div>
                <div class="sub-sidebar-content">

                </div>
            </article>
            <article class="subSidebar"> 
                <div class="sub-sidebar-title">
                    <p>Bai viet moi</p>
                </div>
                <div class="sub-sidebar-content">

                </div>
            </article>
        </div>
        <div class="mainContent"> 
            <div class="input-content">
                <form action="index.html" method="POST">
                    <input id="input-content" name="content" class="input-form" type="text" placeholder="Input todo content">
                    <button type="button" id="button-content" class="button-form">Submit</button>
                </form>
            </div>
            <div class="output-content">
                <ul class="output-item" id="content-output">
                </ul>
            </div>
        </div>
    </section>
    <footer id="footerContainer">
        <p>Copyright &copy; - By Fodo </p>
    </footer>
</body>
<!-- <script src="assets/scripts/jquery/jquery-3.5.1.slim.min.js"></script> -->
<script src="assets/scripts/all.js"></script>
<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
<!-- <script>
  $( function() {
    $( "#content-output" ).sortable();
    $( "#content-output" ).disableSelection();
  } );
  </script> -->
</html>